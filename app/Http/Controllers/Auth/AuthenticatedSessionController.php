<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use App\Models\User;
use App\Models\Mahasiswa;

class AuthenticatedSessionController extends Controller
{
    public function create(): View
    {
        return view('loginpage');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'user' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        $loginInput = $request->input('user');
        $password = $request->input('password'); 
        
        $isNIM = is_numeric($loginInput);

        if ($isNIM) {
            // --- LOGIN MAHASISWA ---
            $mahasiswa = Mahasiswa::where('nim', $loginInput)->first();

            // Cek Password Biasa (Plain Text)
            if ($mahasiswa && $mahasiswa->password === $password) {
                Auth::guard('mahasiswa')->login($mahasiswa, $request->boolean('remember'));
                $request->session()->regenerate();
                return redirect()->intended(route('dashboard'));
            }
        } else {
            // --- LOGIN ADMIN (FIXED: Cek kolom 'username') ---
            $admin = User::where('username', $loginInput)->first(); // Ganti dari email ke username

            // Cek Password Biasa (Plain Text)
            if ($admin && $admin->password === $password) {
                Auth::guard('web')->login($admin, $request->boolean('remember'));
                $request->session()->regenerate();
                return redirect()->intended(route('admin.dashboard'));
            }
        }

        return back()->withErrors([
            'user' => 'NIM/Username atau Password salah.',
        ])->onlyInput('user');
    }

    public function destroy(Request $request): RedirectResponse
    {
        if (Auth::guard('web')->check()) { Auth::guard('web')->logout(); }
        if (Auth::guard('mahasiswa')->check()) { Auth::guard('mahasiswa')->logout(); }

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}