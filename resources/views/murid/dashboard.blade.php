@extends('layouts.murid')

@section('title', 'Dashboard Murid')

@section('content')
    <div class="container-fluid py-4">
        <!-- Header Section -->
        <div class="header-section">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h1 class="mb-2">
                        <i class="bi bi-speedometer2 me-3"></i>
                        Dashboard Murid
                    </h1>
                    <p class="mb-0 opacity-75">Pantau aktivitas latihan dan perkembangan Anda di Dragon Eight Taekwondo Club</p>
                </div>
            </div>
        </div>

        <!-- Welcome Message -->
        <div class="welcome-section">
            <div class="alert alert-primary shadow-sm mb-0">
                <h4 class="fw-bold mb-1">Selamat Datang, {{ $murid->nama ?? Auth::user()->name }}!</h4>
                <p class="mb-0">
                    Dashboard Dragon Eight Taekwondo Club - Pantau progress latihan Anda
                    @if($murid && $murid->unit)
                        <br><small class="opacity-75">Unit: {{ $murid->unit->nama_unit }} | Sabuk: {{ $murid->tingkat_sabuk ?? 'Putih' }}</small>
                    @endif
                </p>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="stats-card">
                    <div class="stats-number text-primary">{{ $dashboardData['totalJadwal'] ?? 0 }}</div>
                    <div class="stats-label">Jadwal Terdaftar</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stats-card">
                    <div class="stats-number text-warning">{{ $dashboardData['totalIuranBelumBayar'] ?? 0 }}</div>
                    <div class="stats-label">Iuran Belum Bayar</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stats-card">
                    <div class="stats-number text-success">{{ $dashboardData['totalUjianTerdaftar'] ?? 0 }}</div>
                    <div class="stats-label">Ujian Terdaftar</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stats-card">
                    <div class="stats-number text-info">{{ $dashboardData['totalAbsensi'] ?? 0 }}</div>
                    <div class="stats-label">Total Absensi</div>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        // Print functionality
        window.addEventListener('beforeprint', function() {
            document.querySelector('.header-section .btn').style.display = 'none';
        });

        window.addEventListener('afterprint', function() {
            document.querySelector('.header-section .btn').style.display = 'block';
        });
    </script>
@endsection