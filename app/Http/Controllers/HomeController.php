<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;


class HomeController extends Controller
{
   public function index()
{
    $user = Auth::user();

    if (!$user) {
        return redirect()->route('login');
    }

    $role = $user->getRoleNames()->toArray();
    $permissionNames = $user->getPermissionNames();

    if ($permissionNames->contains('dashboard-view')) {
        return redirect()->route('admin.dashboard.index');
    } else {
        return view('Admin.Dashboard.index');
    }
}

    public function getTimezones(Request $request)
    {
        $country = $request->input('country'); // Retrieve the 'country' parameter from the request
        $timezones = [];
        // dd($country);
        if ($country) {
            $allTimezones = getTimezones(); // Get all timezones
            $timezones = $allTimezones[$country] ?? [];
        }

        return response()->json(['timezones' => $timezones]);
    }
    public function dummy()
    {
        $employees = Employee::whereHas('user', function ($query) {
            $query->where('status', 'active');
        })->with('user')->get();

        return view('Admin.Dummy.index', compact('employees'));
    }

}
