@extends('layouts.murid')

@section('title', 'Profil Saya')

@section('content')
<div class="container-fluid px-4">
    <!-- Header -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Profil Saya</h1>
    </div>

    <div class="row">
        <!-- Profile Information -->
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">Informasi Profil</h6>
                    <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#editProfileModal">
                        <i class="fas fa-edit"></i> Edit Profil
                    </button>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted small">Nama Lengkap</label>
                            <p class="form-control-plaintext border-bottom">{{ $murid->nama ?? 'Belum diisi' }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted small">Email</label>
                            <p class="form-control-plaintext border-bottom">{{ Auth::user()->email }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted small">Nomor Telepon</label>
                            <p class="form-control-plaintext border-bottom">{{ $murid->no_telp ?? 'Belum diisi' }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted small">Tanggal Lahir</label>
                            <p class="form-control-plaintext border-bottom">
                                {{ $murid->tanggal_lahir ? \Carbon\Carbon::parse($murid->tanggal_lahir)->format('d M Y') : 'Belum diisi' }}
                            </p>
                        </div>
                        <div class="col-12 mb-3">
                            <label class="form-label text-muted small">Alamat</label>
                            <p class="form-control-plaintext border-bottom">{{ $murid->alamat ?? 'Belum diisi' }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Training Information -->
        <div class="col-lg-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-success">Informasi Latihan</h6>
                </div>
                <div class="card-body">
                    <div class="text-center mb-4">
                        <div class="bg-success text-white rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3" 
                             style="width: 80px; height: 80px;">
                            <i class="fas fa-medal fa-2x"></i>
                        </div>
                        <h5 class="font-weight-bold">{{ $murid->tingkat_sabuk ?? 'Putih' }}</h5>
                        <p class="text-muted small">Tingkat Sabuk</p>
                    </div>
                    
                    <hr>
                    
                    <div class="row text-center">
                        <div class="col-6">
                            <h6 class="font-weight-bold text-primary">{{ $murid->unit->nama_unit ?? 'Belum dipilih' }}</h6>
                            <p class="text-muted small">Unit</p>
                        </div>
                        <div class="col-6">
                            <h6 class="font-weight-bold text-info">
                                {{ $murid->tanggal_bergabung ? \Carbon\Carbon::parse($murid->tanggal_bergabung)->format('M Y') : '-' }}
                            </h6>
                            <p class="text-muted small">Bergabung</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Stats -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-info">Statistik</h6>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-12 mb-3">
                            <h4 class="font-weight-bold text-primary">{{ now()->diffInMonths($murid->tanggal_bergabung ?? now()) }}</h4>
                            <p class="text-muted small">Bulan Berlatih</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Edit Profile Modal -->
<div class="modal fade" id="editProfileModal" tabindex="-1" aria-labelledby="editProfileModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editProfileModalLabel">Edit Profil</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editProfileForm" action="{{ route('murid.profile.update') }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="nama" class="form-label">Nama Lengkap</label>
                            <input type="text" class="form-control" id="nama" name="nama" value="{{ $murid->nama ?? '' }}" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" value="{{ Auth::user()->email }}" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="no_telp" class="form-label">Nomor Telepon</label>
                            <input type="text" class="form-control" id="no_telp" name="no_telp" value="{{ $murid->no_telp ?? '' }}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="tanggal_lahir" class="form-label">Tanggal Lahir</label>
                            <input type="date" class="form-control" id="tanggal_lahir" name="tanggal_lahir" value="{{ $murid->tanggal_lahir ?? '' }}">
                        </div>
                        <div class="col-12 mb-3">
                            <label for="alamat" class="form-label">Alamat</label>
                            <textarea class="form-control" id="alamat" name="alamat" rows="3">{{ $murid->alamat ?? '' }}</textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Handle form submission
    $('#editProfileForm').on('submit', function(e) {
        e.preventDefault();
        
        // You can add AJAX submission here if needed
        // For now, let's use regular form submission
        this.submit();
    });
</script>
@endsection