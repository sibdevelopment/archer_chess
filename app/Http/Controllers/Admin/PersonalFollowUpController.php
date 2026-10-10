<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PersonalFollowUp;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class PersonalFollowUpController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $query = PersonalFollowUp::where('user_id', $user->id)->orderByRaw("FIELD(status, 'PENDING', 'DONE', 'CANCELLED')")
            ->orderBy('follow_up_at', 'asc');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $followUps = $query->paginate(50)->withQueryString();
        $counts = $this->counts($user->id);

        return view('Admin.PersonalFollowUps.index', compact('followUps', 'counts'));
    }

    public function create()
    {
        return view('Admin.PersonalFollowUps.form');
    }

    public function store(Request $request)
    {
        $data = $request->validate($this->rules(true));
        $data['user_id'] = auth()->id();
        $data['created_by'] = auth()->id();
        $data['updated_by'] = auth()->id();
        $data['status'] = $data['status'] ?? 'PENDING';

        PersonalFollowUp::create($data);

        return redirect()->route('admin.personal-follow-ups.index')->with('success', 'Follow-up created successfully.');
    }

    public function edit(PersonalFollowUp $personal_follow_up)
    {
        $this->authorizeOwner($personal_follow_up);

        return view('Admin.PersonalFollowUps.form', ['followUp' => $personal_follow_up]);
    }

    public function update(Request $request, PersonalFollowUp $personal_follow_up)
    {
        $this->authorizeOwner($personal_follow_up);

        $data = $request->validate($this->rules());
        $data['updated_by'] = auth()->id();
        $personal_follow_up->update($data);

        return redirect()->route('admin.personal-follow-ups.index')->with('success', 'Follow-up updated successfully.');
    }

    private function rules(bool $creating = false): array
    {
        $followUpAtRules = ['required', 'date'];
        if ($creating) {
            $followUpAtRules[] = 'after_or_equal:now';
        }

        return [
            'follow_up_at' => $followUpAtRules,
            'related_to' => 'nullable|string|max:191',
            'related_name' => 'nullable|string|max:191',
            'follow_up_type' => 'nullable|string|max:191',
            'what_to_do' => 'required|string',
            'priority' => 'required|in:LOW,MEDIUM,HIGH,CRITICAL',
            'status' => 'required|in:PENDING,DONE,CANCELLED',
            'reason_note' => 'nullable|string',
        ];
    }

    private function counts(int $userId): array
    {
        $todayStart = Carbon::today();
        $todayEnd = Carbon::today()->endOfDay();

        return [
            'due_today' => PersonalFollowUp::where('user_id', $userId)->where('status', 'PENDING')->whereBetween('follow_up_at', [$todayStart, $todayEnd])->count(),
            'upcoming' => PersonalFollowUp::where('user_id', $userId)->where('status', 'PENDING')->where('follow_up_at', '>', $todayEnd)->count(),
            'overdue' => PersonalFollowUp::where('user_id', $userId)->where('status', 'PENDING')->where('follow_up_at', '<', $todayStart)->count(),
            'done' => PersonalFollowUp::where('user_id', $userId)->where('status', 'DONE')->count(),
        ];
    }

    private function authorizeOwner(PersonalFollowUp $followUp): void
    {
        abort_if($followUp->user_id !== auth()->id(), 403);
    }
}
