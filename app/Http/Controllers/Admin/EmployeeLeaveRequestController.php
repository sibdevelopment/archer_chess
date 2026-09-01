<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\EmployeeLeaveRequest;
use App\Models\Role;
use DataTables;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class EmployeeLeaveRequestController extends Controller
{
    public function index()
    {
        $employees = Employee::with('user.roles')
            ->whereHas('user', fn ($query) => $query->where('status', 'ACTIVE'))
            ->get();

        $roles = Role::whereNotIn('name', getSystemRoles())
            ->where('status', 'ACTIVE')
            ->orderBy('name')
            ->get();

        return view('Admin.EmployeeLeaveRequests.index', compact('employees', 'roles'));
    }

    public function data(Request $request)
    {
        $user = Auth::user();
        $query = EmployeeLeaveRequest::with(['employee.user.roles', 'approver'])
            ->select('employee_leave_requests.*')
            ->orderByDesc('from_date')
            ->orderByDesc('id');

        if (! $user->hasRole('SuperAdmin')) {
            $employee = Employee::where('user_id', $user->id)->first();
            if (! $employee) {
                return DataTables::of([])->toJson();
            }
            $query->where('employee_id', $employee->id);
        }

        if ($request->filled('employee_id')) {
            $query->where('employee_id', $request->employee_id);
        }

        if ($request->filled('role')) {
            $query->whereHas('employee.user.roles', function ($roleQuery) use ($request) {
                $roleQuery->where('name', $request->role);
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('leave_type')) {
            $query->where('leave_type', $request->leave_type);
        }

        if ($request->filled('from_date')) {
            $query->whereDate('from_date', '>=', $request->from_date);
        }

        if ($request->filled('to_date')) {
            $query->whereDate('to_date', '<=', $request->to_date);
        }

        return DataTables::eloquent($query)
            ->addColumn('employee_name', function ($leave) {
                return $leave->employee?->user?->full_name ?? 'N/A';
            })
            ->filterColumn('employee_name', function ($query, $keyword) {
                $query->whereHas('employee.user', function ($userQuery) use ($keyword) {
                    $userQuery->where('first_name', 'like', "%{$keyword}%")
                        ->orWhere('last_name', 'like', "%{$keyword}%")
                        ->orWhere('email', 'like', "%{$keyword}%");
                });
            })
            ->addColumn('role_name', function ($leave) {
                return $leave->employee?->user?->roles->pluck('name')->implode(', ') ?: 'N/A';
            })
            ->editColumn('timeline', function ($leave) {
                $fromDate = Carbon::parse($leave->from_date)->format('d-M-Y');
                $toDate = Carbon::parse($leave->to_date)->format('d-M-Y');
                $fromTime = $leave->from_time ? Carbon::parse($leave->from_time)->format('h:i A') : '';
                $toTime = $leave->to_time ? Carbon::parse($leave->to_time)->format('h:i A') : '';
                $dateRange = $fromDate === $toDate ? $fromDate : "{$fromDate} to {$toDate}";
                $timeRange = ($fromTime && $toTime) ? " ({$fromTime} to {$toTime})" : '';

                return $dateRange . $timeRange;
            })
            ->editColumn('reason', fn ($leave) => e($leave->reason))
            ->editColumn('rejection_reason', fn ($leave) => e($leave->rejection_reason ?? ''))
            ->addColumn('approved_by_name', function ($leave) {
                return $leave->approver ? $leave->approver->full_name : '';
            })
            ->editColumn('approved_at', function ($leave) {
                return $leave->approved_at ? Carbon::parse($leave->approved_at)->format('d-M-Y h:i A') : '';
            })
            ->editColumn('applied_on', function ($leave) {
                return $leave->created_at ? Carbon::parse($leave->created_at)->format('d-M-Y h:i A') : '';
            })
            ->editColumn('status', function ($leave) use ($user) {
                $badgeColor = match ($leave->status) {
                    'APPROVED' => 'success',
                    'REJECTED' => 'danger',
                    default => 'warning',
                };

                if ($user->hasRole('SuperAdmin') && $leave->status === 'PENDING') {
                    return '<button type="button" class="btn badge bg-' . $badgeColor . ' fs-1 employeeleave-status-switch" data-bs-toggle="modal" data-bs-target="#statusChangeModal" data-id="' . $leave->id . '">' . $leave->status . '</button>';
                }

                return '<span class="badge bg-' . $badgeColor . ' fs-1">' . $leave->status . '</span>';
            })
            ->addColumn('action', function ($leave) {
                if ($leave->status !== 'PENDING') {
                    return '<span class="text-muted">-</span>';
                }

                return '<a href="' . route('admin.employeeleaverequests.edit', ['employeeleaverequest' => $leave->id]) . '" class="badge bg-warning fs-1"><i class="fa fa-edit"></i></a>';
            })
            ->addIndexColumn()
            ->rawColumns(['status', 'action'])
            ->setRowId('id')
            ->make(true);
    }

    public function create()
    {
        $employee = Employee::where('user_id', Auth::id())->with('user')->first();
        if (! $employee) {
            return redirect()->back()->withErrors('Employee data not found.');
        }

        return view('Admin.EmployeeLeaveRequests.form', compact('employee'));
    }

    public function store(Request $request)
    {
        $employee = Employee::where('user_id', Auth::id())->firstOrFail();
        $validated = $this->validatedLeaveData($request);
        $validated['employee_id'] = $employee->id;
        $validated['status'] = 'PENDING';

        $leave = EmployeeLeaveRequest::create($validated);

        return response()->json([
            'status' => 'success',
            'message' => 'Employee leave request created successfully',
            'leave' => $leave,
        ], 201);
    }

    public function edit(EmployeeLeaveRequest $employeeleaverequest)
    {
        if (! $this->canManage($employeeleaverequest)) {
            abort(403);
        }

        if ($employeeleaverequest->status !== 'PENDING') {
            return redirect()->route('admin.employeeleaverequests.index')->withErrors('Only pending leave requests can be edited.');
        }

        $employee = $employeeleaverequest->employee;

        return view('Admin.EmployeeLeaveRequests.form', [
            'employee' => $employee,
            'employeeleaverequest' => $employeeleaverequest,
        ]);
    }

    public function show(EmployeeLeaveRequest $employeeleaverequest)
    {
        if (! $this->canManage($employeeleaverequest)) {
            abort(403);
        }

        return response()->json($employeeleaverequest->load(['employee.user.roles', 'approver']));
    }

    public function update(Request $request, EmployeeLeaveRequest $employeeleaverequest)
    {
        if (! $this->canManage($employeeleaverequest)) {
            abort(403);
        }

        if ($employeeleaverequest->status !== 'PENDING') {
            return response()->json([
                'status' => 'error',
                'message' => 'Only pending leave requests can be updated.',
            ], 422);
        }

        $employeeleaverequest->update($this->validatedLeaveData($request));

        return response()->json([
            'status' => 'success',
            'message' => 'Employee leave request updated successfully',
            'leave' => $employeeleaverequest,
        ], 201);
    }

    public function changeStatus(Request $request)
    {
        $request->validate([
            'employee_leave_request_id' => ['required', 'exists:employee_leave_requests,id'],
            'status' => ['required', Rule::in(['APPROVED', 'REJECTED'])],
            'rejection_reason' => ['required_if:status,REJECTED', 'nullable', 'string', 'max:1000'],
        ]);

        if (! Auth::user()->hasRole('SuperAdmin')) {
            abort(403);
        }

        $leave = EmployeeLeaveRequest::findOrFail($request->employee_leave_request_id);
        if ($leave->status !== 'PENDING') {
            return response()->json([
                'status' => 'error',
                'message' => 'Only pending leave requests can be approved or rejected.',
            ], 422);
        }

        $leave->status = $request->status;
        $leave->rejection_reason = $request->status === 'REJECTED' ? $request->rejection_reason : null;
        $leave->approved_by = Auth::id();
        $leave->approved_at = now();
        $leave->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Employee leave request marked ' . strtolower($leave->status) . ' successfully',
            'leave' => $leave,
        ]);
    }

    private function validatedLeaveData(Request $request): array
    {
        $validated = $request->validate([
            'from_date' => ['required', 'date'],
            'to_date' => ['required', 'date', 'after_or_equal:from_date'],
            'from_time' => ['nullable', 'required_with:to_time', 'date_format:H:i'],
            'to_time' => ['nullable', 'required_with:from_time', 'date_format:H:i', 'after:from_time'],
            'leave_type' => ['required', Rule::in(['FULL DAY', 'HALF DAY', 'SHORT LEAVE', 'SICK LEAVE', 'EMERGENCY', 'OTHER'])],
            'reason' => ['required', 'string', 'max:1000'],
        ]);

        if (($validated['to_time'] ?? null) === '00:00') {
            throw ValidationException::withMessages([
                'to_time' => ['Please use 11:59 PM as the day-ending leave time. Do not use 12:00 AM for the same day.'],
            ]);
        }

        return $validated;
    }

    private function canManage(EmployeeLeaveRequest $leave): bool
    {
        $user = Auth::user();
        if ($user->hasRole('SuperAdmin')) {
            return true;
        }

        return (int) $leave->employee?->user_id === (int) $user->id;
    }
}
