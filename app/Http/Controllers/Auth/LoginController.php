<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * THIS IS THE CORRECT WAY (SAFE REDIRECTION)
     */
  protected function authenticated(Request $request, $user)
{
    if (!$user->is_active) {
        Auth::logout();
        return redirect('/login')->withErrors(['email' => 'Your account has been deactivated. Please contact the administrator.']);
    }

    if ($user->role == 'admin') {
        return redirect('/admin/dashboard');
    }

    if ($user->role == 'supervisor') {
        return redirect('/supervisor/dashboard');
    }

    if ($user->role == 'student') {
        return redirect('/student/dashboard');
    }

    Auth::logout();
    return redirect('/login');
}
}