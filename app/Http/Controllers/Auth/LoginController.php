<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('auth')->only('logout');
    }

    /**
     * Handle authenticated user
     */
    protected function authenticated(Request $request, $user)
    {
        // Redirect berdasarkan role user
        if ($user->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }

        // Redirect ke homepage untuk user biasa
        return redirect('/');
    }
}
