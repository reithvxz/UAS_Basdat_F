@extends('layouts.frontend')
@section('title', 'Dashboard | SIMAS-FTMM')

@section('navbar')
    <nav>
        <div class="logo">SIMAS-FTMM</div>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <a href="{{ route('logout') }}" class="logout" onclick="event.preventDefault(); this.closest('form').submit();">
                Logout
            </a>
        </form>
    </nav>
@endsection

@section('content')
    <div class="container">
        <h1 class="title">SIMAS-FTMM</h1>
        <p class="subtitle">Sistem Manajemen Persuratan FTMM</p>
        <p class="welcome">Halo, <b>{{ Auth::guard('mahasiswa')->user()->nama }}!</b></p>

        <div class="menu-grid">
            <a href="{{ route('pengajuan.create') }}" class="card">
                <div class="card-icon">📩</div>
                <h3>Pengajuan Surat</h3>
                <p>Ajukan surat secara online dengan mudah.</p>
            </a>
            <a href="{{ route('status') }}" class="card">
                <div class="card-icon">📊</div>
                <h3>Status Surat</h3>
                <p>Lacak status suratmu secara real-time.</p>
            </a>
            <a href="{{ route('template.index') }}" class="card">
                <div class="card-icon">📄</div>
                <h3>Template Surat</h3>
                <p>Gunakan template surat resmi dengan cepat.</p>
            </a>
        </div>
    </div>
@endsection