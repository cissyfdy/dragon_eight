@extends('layouts.murid')

@section('title', 'Profil Saya')

@section('content')
<div class="container-fluid py-4">
    <!-- Header Section & Informasi Murid -->
    <div class="header-section">
        <div class="row align-items-center">
            <div class="col-md-8">
                <h1 class="mb-2">
                    <i class="bi bi-person me-3"></i>
                    Profil Saya
                </h1>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Profile Information -->
        <div class="col-lg-12">
            <div class="card profile-card mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h3 class="mb-0"><i class="bi bi-person-vcard"></i> Informasi Profil</h3>
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editProfileModal">
                            <i class="bi bi-pencil-square"></i> Edit Profil
                        </button>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="profile-info mb-4">
                                <div>
                                    <i class="bi bi-person-fill"></i>
                                    <strong>Nama Lengkap</strong><br>
                                    <span>{{ $murid->nama ?? $murid->nama_murid ?? 'Belum diisi' }}</span>
                                </div>
                            </div>
                            
                            <div class="profile-info mb-4">
                                <div>
                                    <i class="bi bi-envelope-fill"></i>
                                    <strong>Email</strong><br>
                                    <span>{{ Auth::user()->email ?? 'Belum diisi' }}</span>
                                </div>
                            </div>
                            
                            <div class="profile-info mb-4">
                                <div>
                                    <i class="bi bi-telephone-fill"></i>
                                    <strong>Nomor Telepon</strong><br>
                                    <span>{{ $murid->no_telp ?? $murid->no_hp ?? 'Belum diisi' }}</span>
                                </div>
                            </div>

                            <div class="profile-info mb-4">
                                <div>
                                    <i class="bi bi-door-closed-fill"></i>
                                    <strong>Unit</strong><br>
                                    <span>{{ $murid->unit->nama_unit ?? 'Unit tidak tersedia' }}</span>
                                </div>
                            </div>

                            <div class="profile-info mb-4">
                                <div>
                                    <i class="bi bi-geo-alt-fill"></i>
                                    <strong>Alamat Unit</strong><br>
                                    <span>{{ $murid->unit->alamat ?? 'Unit tidak tersedia' }}</span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="profile-info mb-4">
                                <div>
                                    <i class="bi bi-calendar-fill"></i>
                                    <strong>Tanggal Lahir</strong><br>
                                    <span>
                                        {{ $murid->tanggal_lahir ? \Carbon\Carbon::parse($murid->tanggal_lahir)->format('d M Y') : 'Belum diisi' }}
                                    </span>
                                </div>
                            </div>
                            
                            <div class="profile-info mb-4">
                                <div>
                                    <i class="bi bi-house-door-fill"></i>
                                    <strong>Alamat Rumah</strong><br>
                                    <span>{{ $murid->alamat ?? 'Belum diisi' }}</span>
                                </div>
                            </div>
                            
                            @if(isset($murid->tingkat_sabuk))
                            <div class="profile-info mb-4">
                                <div>
                                    <i class="bi bi-award-fill"></i>
                                    <strong>Tingkat Sabuk</strong><br>
                                    <span>{{ $murid->tingkat_sabuk ?? 'Belum diisi' }}</span>
                                </div>
                            </div>
                            @endif

                            @if(isset($murid->nomor_register))
                            <div class="profile-info mb-4">
                                <div>
                                    <i class="bi bi-credit-card-2-front-fill"></i>
                                    <strong>No. Register</strong><br>
                                    <span>{{ $murid->nomor_register ?? 'Belum diisi' }}</span>
                                </div>
                            </div>
                            @endif
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
                            <label for="nama" class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="nama" name="nama" 
                                   value="{{ old('nama', $murid->nama ?? $murid->nama_murid ?? '') }}" required>
                            @error('nama')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                            <input type="email" class="form-control" id="email" name="email" 
                                   value="{{ old('email', Auth::user()->email) }}" readonly>
                            @error('email')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="no_telp" class="form-label">Nomor Telepon</label>
                            <input type="text" class="form-control" id="no_telp" name="no_telp" 
                                   value="{{ old('no_telp', $murid->no_telp ?? $murid->no_hp ?? '') }}"
                                   placeholder="Contoh: 081234567890">
                            @error('no_telp')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="tanggal_lahir" class="form-label">Tanggal Lahir</label>
                            <input type="date" class="form-control" id="tanggal_lahir" name="tanggal_lahir" 
                                   value="{{ old('tanggal_lahir', $murid->tanggal_lahir ?? '') }}">
                            @error('tanggal_lahir')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-12 mb-3">
                            <label for="alamat" class="form-label">Alamat Rumah</label>
                            <textarea class="form-control" id="alamat" name="alamat" rows="3" 
                                      placeholder="Masukkan alamat lengkap">{{ old('alamat', $murid->alamat ?? '') }}</textarea>
                            @error('alamat')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Display success/error messages -->
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-circle"></i> Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Auto-show modal if there are validation errors
        @if($errors->any())
            var editModal = new bootstrap.Modal(document.getElementById('editProfileModal'));
            editModal.show();
        @endif

        // Auto-hide success/error messages after 5 seconds
        setTimeout(function() {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(function(alert) {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            });
        }, 5000);
    });

    // Handle form submission with loading state
    document.getElementById('editProfileForm').addEventListener('submit', function(e) {
        const submitBtn = this.querySelector('button[type="submit"]');
        const originalText = submitBtn.innerHTML;
        
        // Show loading state
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Menyimpan...';
        
        // Reset button after 3 seconds (in case of error)
        setTimeout(function() {
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalText;
        }, 3000);
    });

    // Phone number formatting
    document.getElementById('no_telp').addEventListener('input', function(e) {
        let value = e.target.value.replace(/\D/g, ''); // Remove non-digits
        
        // Limit to reasonable phone number length
        if (value.length > 15) {
            value = value.substring(0, 15);
        }
        
        e.target.value = value;
    });
</script>
@endsection