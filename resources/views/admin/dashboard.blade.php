@extends('layout')

@section('konten')
    <title>Dashboard Admin - Dragon Eight</title>

    <style>
        
        
    </style>

    <div class="container-fluid py-4">
        <!-- Header Section -->
        <div class="header-section">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h1 class="mb-2">
                        <i class="bi bi-speedometer2 me-3"></i>
                        Dashboard Admin
                    </h1>
                    <p class="mb-0 opacity-75">Kelola dan pantau aktivitas Dragon Eight Taekwondo Club</p>
                </div>
            </div>
        </div>

        <!-- Welcome Message -->
        <div class="welcome-section">
            <div class="alert alert-primary shadow-sm mb-0">
                <h4 class="fw-bold mb-1">Selamat Datang, {{ Auth::user()->name }}!</h4>
                <p class="mb-0">Dashboard Dragon Eight Taekwondo Club - Kelola aktivitas taekwondo Anda</p>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="stats-card">
                    <div class="stats-number text-primary">0</div>
                    <div class="stats-label">Murid</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stats-card">
                    <div class="stats-number text-success">0</div>
                    <div class="stats-label">Pelatih</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stats-card">
                    <div class="stats-number text-info">0</div>
                    <div class="stats-label">Jadwal</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stats-card">
                    <div class="stats-number text-warning">0</div>
                    <div class="stats-label">Unit</div>
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