@extends('layout')

@section('konten')
    <title>Profil Pelatih</title>

    <style>
        .dropdown-menu {
            z-index: 1050; 
        }
        
        .badge-hari {
            font-size: 0.875rem;
            padding: 0.375rem 0.75rem;
        }
        
        .jadwal-info {
            background-color: #f8f9fa;
            border-radius: 8px;
            padding: 0.5rem;
            margin: 0.25rem 0;
        }
        
        .time-badge {
            background-color: #e3f2fd;
            color: #1565c0;
            border: 1px solid #bbdefb;
        }
        
        .student-count {
            background-color: #f3e5f5;
            color: #7b1fa2;
            border: 1px solid #e1bee7;
        }
        
        .unit-badge {
            background-color: #e8f5e8;
            color: #2e7d32;
            border: 1px solid #c8e6c9;
        }
        
        .profile-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 15px;
        }
        
        .profile-card .card-body {
            padding: 2rem;
        }
        
        .profile-info {
            display: flex;
            align-items: center;
            margin-bottom: 1rem;
        }
        
        .profile-info i {
            margin-right: 1rem;
            font-size: 1.2rem;
            width: 20px;
        }
        
        .stats-card {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 10px;
            padding: 1rem;
            text-align: center;
        }
        
        .stats-number {
            font-size: 2rem;
            font-weight: bold;
            margin-bottom: 0.5rem;
        }
        
        .stats-label {
            font-size: 0.875rem;
            opacity: 0.9;
        }
    </style>

    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <!-- Header Section -->
                
                
                <!-- Profile Card -->
                <div class="card profile-card mb-4">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h2 class="mb-0"><i class="bi bi-person"></i> Profil Pelatih</h2>
                        </div>
                        <div class="row">
                            <div class="col-md-8">                                
                                <div class="profile-info">
                                    <i class="bi bi-person-fill"></i>
                                    <span>{{ $pelatih->nama_pelatih }}</span>
                                </div>
                                
                                <div class="profile-info">
                                    <i class="bi bi-geo-alt-fill"></i>
                                    <span>{{ $pelatih->alamat ?? 'Alamat tidak tersedia' }}</span>
                                </div>
                                
                                <div class="profile-info">
                                    <i class="bi bi-telephone-fill"></i>
                                    <span>{{ $pelatih->no_hp ?? 'No. HP tidak tersedia' }}</span>
                                </div>
                            </div>
                            
                            <div class="col-md-4">
                                <div class="stats-card">
                                    <div class="stats-number">{{ $jadwal->count() }}</div>
                                    <div class="">Jadwal Mengajar</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Jadwal Mengajar Section -->
                <div class="table-container">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h2 class="mb-0"><i class="bi bi-calendar3 fs-1 me-2"></i>Jadwal Mengajar {{ $pelatih->nama_pelatih }}</h2>
                    </div>
                    
                    @if($jadwal->count() > 0)
                        <div class="card">
                            <div class="card-body">
                                <table class="table table-hover mb-0">
                                    <thead>
                                        <tr>
                                            <th>Hari</th>
                                            <th>Jam</th>
                                            <th>Unit</th>
                                            <th>Jumlah Murid</th>
                                            <th>Keterangan</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($jadwal as $j)
                                        <tr>
                                            <td>
                                                <span class="badge badge-hari 
                                                    @switch($j->hari)
                                                        @case('Senin') bg-primary @break
                                                        @case('Selasa') bg-success @break
                                                        @case('Rabu') bg-info @break
                                                        @case('Kamis') bg-warning text-dark @break
                                                        @case('Jumat') bg-danger @break
                                                        @case('Sabtu') bg-secondary @break
                                                        @case('Minggu') bg-dark @break
                                                        @default bg-light text-dark @break
                                                    @endswitch
                                                ">
                                                    {{ $j->hari }}
                                                </span>
                                            </td>
                                            <td>
                                                <div class="jadwal-info">
                                                    <span class="badge time-badge">
                                                        {{ date('H:i', strtotime($j->jam_mulai)) }} - {{ date('H:i', strtotime($j->jam_selesai)) }}
                                                    </span>
                                                </div>
                                            </td>
                                            <td>
                                                <div>
                                                    <span class="badge unit-badge">
                                                        {{ $j->nama_unit }}
                                                    </span>
                                                    <br>
                                                    <small class="text-muted">{{ Str::limit($j->alamat_unit, 50) }}</small>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="badge student-count">
                                                    {{ $j->jumlah_murid }} Murid
                                                </span>
                                            </td>
                                            <td>
                                                <small class="text-muted">{{ $j->keterangan ?? 'Tidak ada keterangan' }}</small>
                                            </td>
                                            <td>
                                                <span class="badge bg-success">Aktif</span>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        
                        <!-- Summary Section -->
                        <div class="row mt-4">
                            <div class="col-md-3">
                                <div class="card text-center">
                                    <div class="card-body">
                                        <h5 class="card-title text-primary">{{ $jadwal->count() }}</h5>
                                        <p class="card-text">Total Jadwal</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card text-center">
                                    <div class="card-body">
                                        <h5 class="card-title text-success">{{ $jadwal->sum('jumlah_murid') }}</h5>
                                        <p class="card-text">Total Murid</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card text-center">
                                    <div class="card-body">
                                        <h5 class="card-title text-info">{{ $jadwal->unique('unit_id')->count() }}</h5>
                                        <p class="card-text">Unit Mengajar</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                    @else
                        <div class="alert alert-info">
                            <i class="bi bi-info-circle"></i> {{ $pelatih->nama_pelatih }} belum memiliki jadwal mengajar.
                        </div>
                    @endif
                </div>
                
                <!-- Schedule by Day Section -->
                @if($jadwal->count() > 0)
                <div class="mt-5">
                    <h3 class="mb-4"><i class="bi bi-calendar-week me-2"></i>Jadwal per Hari</h3>
                    <div class="row">
                        @php
                            $hariList = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'];
                            $jadwalPerHari = $jadwal->groupBy('hari');
                        @endphp
                        
                        @foreach($hariList as $hari)
                            @if($jadwalPerHari->has($hari))
                                <div class="col-md-6 col-lg-4 mb-3">
                                    <div class="card h-100">
                                        <div class="card-header bg-primary text-white">
                                            <h6 class="mb-0">{{ $hari }}</h6>
                                        </div>
                                        <div class="card-body">
                                            @foreach($jadwalPerHari[$hari] as $jadwalHari)
                                                <div class="mb-2 p-2 bg-light rounded">
                                                    <div class="d-flex justify-content-between align-items-center">
                                                        <span class="fw-bold">{{ date('H:i', strtotime($jadwalHari->jam_mulai)) }} - {{ date('H:i', strtotime($jadwalHari->jam_selesai)) }}</span>
                                                        <span class="badge bg-secondary">{{ $jadwalHari->jumlah_murid }}</span>
                                                    </div>
                                                    <small class="text-muted">{{ $jadwalHari->nama_unit }}</small>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>
                @endif
                
            </div>
        </div>
    </div>
@endsection