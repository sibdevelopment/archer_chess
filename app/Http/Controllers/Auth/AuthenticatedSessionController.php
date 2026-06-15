<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;
use Illuminate\Support\Facades\Session;


class AuthenticatedSessionController extends Controller
{

    public function studentLoginPage(Request $request)
    {
        // dd($request->all());
        if ($request->has('user_id')) {
            // dd($request->impersonate);

            // Store admin id (for revert)
            Session::put('impersonator_id', auth()->id());

            // Logout admin
            Auth::logout();

            // Login as student
            Auth::loginUsingId($request->user_id);

            // Redirect directly to student dashboard
            return redirect()->route('admin.student.dashboard');
        }

        // Normal student login page
        return view('Admin.Auth.studentlogin');
    }
    
    public function coachLoginPage(Request $request)
    {
        abort_unless(Auth::check(), 403);

        if ($request->filled('user_id')) {

            // store admin id
            Session::put('impersonator_id', Auth::id());

            // logout admin
            Auth::logout();

            // login as coach
            Auth::loginUsingId($request->user_id);

            // redirect to coach dashboard
            return redirect('/admin/dashboard/dashboard');
        }

        return redirect()->back();
    }


    public function create(): View
    {
        return view('Admin.Auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {

        $request->authenticate();

        $request->session()->regenerate();

        return redirect()->intended(RouteServiceProvider::HOME);
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $user = Auth::user();
        $roleName = $user->roles->pluck('name')->first();
        if ($roleName == 'Student') {
            Auth::guard('web')->logout();

            $request->session()->invalidate();

            $request->session()->regenerateToken();

            return redirect('/student/login');
        } else {
            Auth::guard('web')->logout();

            $request->session()->invalidate();

            $request->session()->regenerateToken();

            return redirect('/login');
        }

    }

    public function studentLogin(Request $request)
    {
        $request->validate([
            'mobile'   => 'required',
            'password' => 'required|string|min:4', // OTP or password vnalidation
        ]);

        $user = User::where('device_id', $request->password)->where('mobile', $request->mobile)->first();
        // $user = User::where('mobile', $request->mobile)->first();
        if (! $user) {
            return back()->with('error', 'Invalid login credentials');
        }

        $roleName = $user->roles->pluck('name')->first();

        // if ($roleName !== 'Student') {
        //     return back()->with('error', 'Access denied. You are a Student');
        // }

        if (! Hash::check($request->password, $user->password)) {
            return back()->with('error', 'Invalid login credentials');
        }

        Auth::login($user);
        return redirect()->intended(RouteServiceProvider::STUDENTHOME);
    }

}
