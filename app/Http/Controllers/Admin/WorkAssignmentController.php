<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\WorkAssignment;
use App\Models\WorkAssignmentHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Validation\Rule;

class WorkAssignmentController extends Controller
{
    public function handovers(Request $request)
    {
        return $this->listing($request, WorkAssignment::TYPE_HANDOVER, 'Admin.WorkAssignments.handovers');
    }

    public function createHandover()
    {
        $assignees = $this->assignableUsers(WorkAssignment::TYPE_HANDOVER);
        return view('Admin.WorkAssignments.handover-form', compact('assignees'));
    }

    public function storeHandover(Request $request)
    {
        $data = $request->validate($this->handoverRules());
        $assignment = $this->createAssignment(WorkAssignment::TYPE_HANDOVER, $data);

        return redirect()->route('admin.shift-handovers.index')->with('success', 'Shift handover created successfully.');
    }

    public function tasks(Request $request)
    {
        return $this->listing($request, WorkAssignment::TYPE_TASK, 'Admin.WorkAssignments.tasks');
    }

    public function createTask()
    {
        $assignees = $this->assignableUsers(WorkAssignment::TYPE_TASK);
        return view('Admin.WorkAssignments.task-form', compact('assignees'));
    }

    public function storeTask(Request $request)
    {
        $data = $request->validate($this->taskRules());
        $assignment = $this->createAssignment(WorkAssignment::TYPE_TASK, $data);

        return redirect()->route('admin.task-assignments.index')->with('success', 'Task created successfully.');
    }

    public function assigned(Request $request)
    {
        $type = $request->input('type');
        $allowedTypes = $this->allowedTypes('view');

        abort_if(empty($allowedTypes), 403);
        if (in_array($type, [WorkAssignment::TYPE_HANDOVER, WorkAssignment::TYPE_TASK], true)) {
            abort_unless(in_array($type, $allowedTypes, true), 403);
        }

        $query = WorkAssignment::with(['creator', 'currentOwner'])
            ->where('current_owner_id', auth()->id())
            ->whereIn('type', $allowedTypes)
            ->when(in_array($type, [WorkAssignment::TYPE_HANDOVER, WorkAssignment::TYPE_TASK], true), function ($q) use ($type) {
                $q->where('type', $type);
            })
            ->orderByRaw("FIELD(status, 'PENDING', 'REASSIGNED', 'DONE', 'CANCELLED')")
            ->latest();

        $assignments = $query->paginate(50)->withQueryString();
        $assigneesByType = [
            WorkAssignment::TYPE_HANDOVER => $this->assignableUsers(WorkAssignment::TYPE_HANDOVER),
            WorkAssignment::TYPE_TASK => $this->assignableUsers(WorkAssignment::TYPE_TASK),
        ];

        return view('Admin.WorkAssignments.assigned', compact('assignments', 'assigneesByType'));
    }

    public function show(WorkAssignment $work_assignment)
    {
        $this->authorizeAssignmentView($work_assignment);
        $work_assignment->load(['creator', 'assignedTo', 'currentOwner', 'histories.actionBy', 'histories.fromUser', 'histories.toUser']);

        return view('Admin.WorkAssignments.show', ['assignment' => $work_assignment]);
    }

    public function updateStatus(Request $request, WorkAssignment $work_assignment)
    {
        $this->authorizeTypePermission($work_assignment->type, 'update');
        $this->authorizeOwnerAction($work_assignment);

        $data = $request->validate([
            'status' => 'required|in:DONE,CANCELLED',
            'final_reason' => 'required_if:status,CANCELLED|nullable|string',
            'assignee_note' => 'nullable|string',
        ]);

        $work_assignment->status = $data['status'];
        $work_assignment->final_reason = $data['final_reason'] ?? null;
        $work_assignment->assignee_note = $data['assignee_note'] ?? null;
        $work_assignment->action_at = Carbon::now();
        $work_assignment->save();

        $this->logHistory($work_assignment, $data['status'], $work_assignment->current_owner_id, $work_assignment->current_owner_id, $data['final_reason'] ?? $data['assignee_note'] ?? null);

        return back()->with('success', 'Work status updated successfully.');
    }

    public function reassign(Request $request, WorkAssignment $work_assignment)
    {
        $this->authorizeTypePermission($work_assignment->type, 'update');
        $this->authorizeOwnerAction($work_assignment);

        $data = $request->validate([
            'reassign_to' => ['required', Rule::exists('users', 'id')],
            'note' => 'required|string',
        ]);

        $newOwner = User::findOrFail($data['reassign_to']);
        $this->validateAssignee($work_assignment->type, $newOwner->id);

        $oldOwner = $work_assignment->current_owner_id;
        $work_assignment->assigned_to = $newOwner->id;
        $work_assignment->current_owner_id = $newOwner->id;
        $work_assignment->status = 'PENDING';
        $work_assignment->action_at = Carbon::now();
        $work_assignment->save();

        $this->logHistory($work_assignment, 'REASSIGNED', $oldOwner, $newOwner->id, $data['note']);

        return back()->with('success', 'Work reassigned successfully.');
    }

    private function listing(Request $request, string $type, string $view)
    {
        $query = WorkAssignment::with(['creator', 'currentOwner'])->where('type', $type)->latest();

        if (! auth()->user()->hasRole('SuperAdmin')) {
            $query->where(function ($q) {
                $q->where('created_by', auth()->id())->orWhere('current_owner_id', auth()->id());
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $assignments = $query->paginate(50)->withQueryString();
        $counts = $this->counts($type);

        return view($view, compact('assignments', 'counts'));
    }

    private function createAssignment(string $type, array $data): WorkAssignment
    {
        $this->validateAssignee($type, (int) $data['assigned_to']);

        $assignment = WorkAssignment::create([
            'type' => $type,
            'created_by' => auth()->id(),
            'assigned_to' => $data['assigned_to'],
            'current_owner_id' => $data['assigned_to'],
            'related_type' => $data['related_type'] ?? null,
            'related_name' => $data['related_name'] ?? null,
            'country' => $data['country'] ?? null,
            'last_action' => $data['last_action'] ?? null,
            'next_action' => $data['next_action'] ?? null,
            'requirement' => $data['requirement'] ?? null,
            'priority' => $data['priority'] ?? 'MEDIUM',
            'status' => 'PENDING',
            'source_note' => $data['source_note'] ?? null,
        ]);

        $this->logHistory($assignment, 'CREATED', auth()->id(), $assignment->current_owner_id, 'Created and assigned.');

        return $assignment;
    }

    private function handoverRules(): array
    {
        return [
            'related_type' => 'required|string|max:191',
            'related_name' => 'required|string|max:191',
            'country' => 'nullable|string|max:191',
            'last_action' => 'required|string',
            'next_action' => 'required|string',
            'assigned_to' => ['required', Rule::exists('users', 'id')],
            'priority' => 'required|in:LOW,MEDIUM,HIGH,CRITICAL',
            'source_note' => 'nullable|string',
        ];
    }

    private function taskRules(): array
    {
        return [
            'related_type' => 'required|string|max:191',
            'related_name' => 'nullable|string|max:191',
            'country' => 'nullable|string|max:191',
            'requirement' => 'required|string',
            'assigned_to' => ['required', Rule::exists('users', 'id')],
            'priority' => 'required|in:LOW,MEDIUM,HIGH,CRITICAL',
            'source_note' => 'nullable|string',
        ];
    }

    private function assignableUsers(string $type)
    {
        $query = User::where('status', 'ACTIVE')->whereHas('roles', function ($q) {
            $q->whereNotIn('name', getSystemRoles());
        })->orderBy('first_name')->orderBy('last_name');

        if ($type === WorkAssignment::TYPE_HANDOVER) {
            $roleNames = $this->handoverRoleNames();
            $query->where('id', '!=', auth()->id())->whereHas('roles', function ($q) use ($roleNames) {
                $q->whereIn('name', $roleNames);
            });
        }

        return $query->get();
    }

    private function validateAssignee(string $type, int $userId): void
    {
        $user = User::where('status', 'ACTIVE')->findOrFail($userId);

        if ($type === WorkAssignment::TYPE_HANDOVER) {
            $roleNames = $this->handoverRoleNames();
            abort_unless($user->id !== auth()->id(), 422, 'Shift handover must be assigned to another employee.');
            abort_unless($user->roles()->whereIn('name', $roleNames)->exists(), 422, 'Shift handover can be assigned only to an employee from the same role.');
        }
    }

    private function handoverRoleNames(): array
    {
        $roleNames = auth()->user()->getRoleNames()
            ->reject(fn ($role) => in_array($role, getSystemRoles(), true))
            ->values()
            ->all();

        if (empty($roleNames) || auth()->user()->hasRole('SuperAdmin')) {
            return ['BDE'];
        }

        return $roleNames;
    }

    private function counts(string $type): array
    {
        $query = WorkAssignment::where('type', $type);
        if (! auth()->user()->hasRole('SuperAdmin')) {
            $query->where(function ($q) {
                $q->where('created_by', auth()->id())->orWhere('current_owner_id', auth()->id());
            });
        }

        $historyQuery = WorkAssignmentHistory::whereHas('assignment', function ($q) use ($type) {
            $q->where('type', $type);
            if (! auth()->user()->hasRole('SuperAdmin')) {
                $q->where(function ($ownerQuery) {
                    $ownerQuery->where('created_by', auth()->id())->orWhere('current_owner_id', auth()->id());
                });
            }
        })->where('action', 'REASSIGNED');

        return [
            'created' => (clone $query)->count(),
            'pending' => (clone $query)->where('status', 'PENDING')->count(),
            'critical' => (clone $query)->where('priority', 'CRITICAL')->where('status', 'PENDING')->count(),
            'done' => (clone $query)->where('status', 'DONE')->count(),
            'cancelled' => (clone $query)->where('status', 'CANCELLED')->count(),
            'reassigned' => $historyQuery->count(),
        ];
    }

    private function logHistory(WorkAssignment $assignment, string $action, ?int $fromUserId, ?int $toUserId, ?string $note): void
    {
        WorkAssignmentHistory::create([
            'work_assignment_id' => $assignment->id,
            'action_by' => auth()->id(),
            'from_user_id' => $fromUserId,
            'to_user_id' => $toUserId,
            'action' => $action,
            'status' => $assignment->status,
            'note' => $note,
        ]);
    }

    private function authorizeAssignmentView(WorkAssignment $assignment): void
    {
        $this->authorizeTypePermission($assignment->type, 'view');

        if (auth()->user()->hasRole('SuperAdmin')) {
            return;
        }

        abort_unless($assignment->created_by === auth()->id() || $assignment->current_owner_id === auth()->id(), 403);
    }

    private function authorizeOwnerAction(WorkAssignment $assignment): void
    {
        abort_unless($assignment->current_owner_id === auth()->id() || auth()->user()->hasRole('SuperAdmin'), 403);
    }

    private function authorizeTypePermission(string $type, string $action): void
    {
        abort_unless(in_array($type, $this->allowedTypes($action), true), 403);
    }

    private function allowedTypes(string $action): array
    {
        $suffix = $action === 'update' ? 'update' : 'view';
        $user = auth()->user();
        $types = [];

        if ($user->can("shift-handovers-{$suffix}")) {
            $types[] = WorkAssignment::TYPE_HANDOVER;
        }

        if ($user->can("task-assignments-{$suffix}")) {
            $types[] = WorkAssignment::TYPE_TASK;
        }

        return $types;
    }
}
