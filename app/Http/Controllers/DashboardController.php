<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Mahasiswa;
class DashboardController extends Controller
{
    public function index()
    {
        if (Auth::guard('mahasiswa')->check()) {
            return view('mahasiswa.dashboard', ['nama' => Auth::guard('mahasiswa')->user()->nama]);
        }
        // Jika bukan mahasiswa, redirect ke login
        return redirect()->route('login');
    }
}