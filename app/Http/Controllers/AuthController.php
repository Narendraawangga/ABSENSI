<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        if (Auth::check()) {
            return $this->redirectBasedOnRole();
        }

        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        $remember = $request->has('remember');

        if (Auth::attempt(['username' => $credentials['username'], 'password' => $credentials['password'], 'is_active' => true], $remember) ||
            Auth::attempt(['email' => $credentials['username'], 'password' => $credentials['password'], 'is_active' => true], $remember)) {

            $request->session()->regenerate();

            return $this->redirectBasedOnRole();
        }

        return back()->withErrors([
            'username' => 'Kredensial yang diberikan tidak cocok dengan data kami atau akun tidak aktif.',
        ])->onlyInput('username');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }

    protected function redirectBasedOnRole()
    {
        $user = Auth::user();
        if ($user->role->slug === 'admin') {
            return redirect()->route('admin.dashboard');
        } elseif ($user->role->slug === 'intern') {
            return redirect()->route('intern.dashboard');
        }

        return redirect('/login');
    }
}
