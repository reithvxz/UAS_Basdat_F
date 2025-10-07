<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View; // Pastikan ini ada
use Illuminate\Http\RedirectResponse;
use App\Models\User;
use App\Models\Mahasiswa;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        // PERUBAHAN: Arahkan ke view login baru Anda
        return view('loginpage');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'user' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        $credentials = $request->only('user', 'password');
        $loginInput = $credentials['user'];
        $isNIM = is_numeric($loginInput);

        if ($isNIM) {
            $mahasiswa = Mahasiswa::where('nim', $loginInput)->first();
            if ($mahasiswa && $mahasiswa->password == $credentials['password']) {
                Auth::guard('mahasiswa')->login($mahasiswa, $request->boolean('remember'));
                $request->session()->regenerate();
                return redirect()->intended(route('dashboard'));
            }
        } else {
            $user = User::where('username', $loginInput)->first();
            if ($user && $user->password == $credentials['password']) {
                Auth::guard('web')->login($user, $request->boolean('remember'));
                $request->session()->regenerate();
                return redirect()->intended(route('admin.dashboard'));
            }
        }

        return back()->withErrors([
            'user' => 'NIM/Username atau Password salah.',
        ])->onlyInput('user');
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        if (Auth::guard('web')->check()) {
            Auth::guard('web')->logout();
        }

        if (Auth::guard('mahasiswa')->check()) {
            Auth::guard('mahasiswa')->logout();
        }

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}