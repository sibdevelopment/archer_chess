<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\WatiNotification;
use App\Services\WatiNotificationService;
use Illuminate\Http\Request;

class WatiNotificationController extends Controller
{
    public function index(Request $request)
    {
        $this->authorizeAccess();

        $notifications = WatiNotification::with([
            'student',
            'initiatedBy',
            'batchClassSession.batch',
        ])
            ->when($request->filled('type'), fn ($query) => $query->where('type', $request->type))
            ->when($request->filled('status'), fn ($query) => $query->where('status', $request->status))
            ->when($request->filled('search'), function ($query) use ($request) {
                $search = trim((string) $request->search);
                $query->where(function ($query) use ($search) {
                    $query->where('recipient', 'like', "%{$search}%")
                        ->orWhere('template_name', 'like', "%{$search}%")
                        ->orWhereHas('student', fn ($student) => $student
                            ->where('first_name', 'like', "%{$search}%")
                            ->orWhere('last_name', 'like', "%{$search}%")
                            ->orWhere('student_id', 'like', "%{$search}%"));
                });
            })
            ->latest('id')
            ->paginate(50)
            ->withQueryString();

        return view('Admin.WatiNotifications.index', compact('notifications'));
    }

    public function retry(WatiNotification $watiNotification, WatiNotificationService $notifications)
    {
        $this->authorizeAccess();

        if (! $watiNotification->canRetry()) {
            return back()->with('error', 'Only failed WATI notifications can be retried.');
        }

        if (! $notifications->availableFor($watiNotification->type)) {
            return back()->with('error', 'WATI credentials or this notification template are not configured.');
        }

        if (! $notifications->allowsNotification($watiNotification)) {
            return back()->with('error', 'This student is not enabled for the current WATI audience mode.');
        }

        try {
            $notifications->dispatch($watiNotification, true);
        } catch (\Throwable $exception) {
            return back()->with('error', 'WATI retry failed: ' . $exception->getMessage());
        }

        return back()->with('success', 'WATI notification retry queued.');
    }

    private function authorizeAccess(): void
    {
        $user = auth()->user();
        $roles = $user?->getRoleNames()->toArray() ?? [];

        abort_unless(
            (bool) array_intersect($roles, ['SuperAdmin', 'Admin', 'Employee'])
                || $user?->can('students-view'),
            403
        );
    }
}
