@extends('layout')

@section('konten')
    <title>Tambah Murid</title>

    <div class="container-fluid py-4">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <!-- Header -->
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0">
                            <i class="bi bi-person-plus me-2"></i>
                            Tambah Murid Baru
                        </h4>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('murid.add') }}" method="POST">
                            @csrf
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="nama_murid" class="form-label">Nama Murid <span class="text-danger">*</span></label>
                                        <input type="text" name="nama_murid" id="nama_murid" class="form-control" 
                                               placeholder="Masukkan nama murid" value="{{ old('nama_murid') }}" required>
                                        @error('nama_murid')
                                            <div class="text-danger small mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="nomor_register" class="form-label">Nomor Register <span class="text-danger">*</span></label>
                                        <input type="number" name="nomor_register" id="nomor_register" class="form-control" 
                                               placeholder="Masukkan nomor register" value="{{ old('nomor_register') }}" required>
                                        @error('nomor_register')
                                            <div class="text-danger small mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="unit_id" class="form-label">Unit <span class="text-danger">*</span></label>
                                        <select name="unit_id" id="unit_id" class="form-select" required>
                                            <option value="">-- Pilih Unit --</option>
                                            @foreach($units as $unit)
                                                <option value="{{ $unit->unit_id }}" {{ old('unit_id') == $unit->unit_id ? 'selected' : '' }}>
                                                    {{ $unit->nama_unit }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('unit_id')
                                            <div class="text-danger small mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="tanggal_lahir" class="form-label">Tanggal Lahir <span class="text-danger">*</span></label>
                                        <input type="date" name="tanggal_lahir" id="tanggal_lahir" class="form-control" 
                                               value="{{ old('tanggal_lahir') }}" required>
                                        @error('tanggal_lahir')
                                            <div class="text-danger small mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="alamat" class="form-label">Alamat <span class="text-danger">*</span></label>
                                <textarea name="alamat" id="alamat" class="form-control" rows="3" 
                                          placeholder="Masukkan alamat lengkap" required>{{ old('alamat') }}</textarea>
                                @error('alamat')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="tingkat_sabuk" class="form-label">Tingkat Sabuk</label>
                                        <select name="tingkat_sabuk" id="tingkat_sabuk" class="form-select">
                                            <option value="">-- Pilih Tingkat Sabuk --</option>
                                            <option value="Sabuk Putih" {{ old('tingkat_sabuk') == 'Sabuk Putih' ? 'selected' : '' }}>Sabuk Putih</option>
                                            <option value="Sabuk Kuning" {{ old('tingkat_sabuk') == 'Sabuk Kuning' ? 'selected' : '' }}>Sabuk Kuning</option>
                                            <option value="Sabuk Hijau" {{ old('tingkat_sabuk') == 'Sabuk Hijau' ? 'selected' : '' }}>Sabuk Hijau</option>
                                            <option value="Sabuk Biru" {{ old('tingkat_sabuk') == 'Sabuk Biru' ? 'selected' : '' }}>Sabuk Biru</option>
                                            <option value="Sabuk Coklat" {{ old('tingkat_sabuk') == 'Sabuk Coklat' ? 'selected' : '' }}>Sabuk Coklat</option>
                                            <option value="Sabuk Hitam" {{ old('tingkat_sabuk') == 'Sabuk Hitam' ? 'selected' : '' }}>Sabuk Hitam</option>
                                        </select>
                                        @error('tingkat_sabuk')
                                            <div class="text-danger small mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="no_hp" class="form-label">No. HP</label>
                                        <input type="text" name="no_hp" id="no_hp" class="form-control" 
                                               placeholder="Masukkan nomor HP" value="{{ old('no_hp') }}">
                                        @error('no_hp')
                                            <div class="text-danger small mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Informasi tambahan -->
                            <div class="alert alert-info">
                                <i class="bi bi-info-circle me-2"></i>
                                <strong>Catatan:</strong> Pastikan nomor register unik untuk setiap murid dan data yang dimasukkan sudah benar.
                            </div>

                            <!-- Tombol aksi -->
                            <div class="d-flex justify-content-between">
                                <a href="{{ route('admin.tampilMurid') }}" class="btn btn-secondary">
                                    <i class="bi bi-arrow-left me-2"></i>
                                    Kembali
                                </a>
                                <div>
                                    <button type="reset" class="btn btn-outline-secondary me-2">
                                        <i class="bi bi-arrow-clockwise me-2"></i>
                                        Reset
                                    </button>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="bi bi-check-circle me-2"></i>
                                        Simpan Murid
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Format nomor HP
        document.getElementById('no_hp').addEventListener('input', function() {
            let value = this.value.replace(/\D/g, '');
            if (value) {
                this.value = value;
            }
        });

        // Show validation errors
        @if($errors->any())
            setTimeout(function() {
                let errors = @json($errors->all());
                alert('Terdapat kesalahan:\n' + errors.join('\n'));
            }, 100);
        @endif
    </script>
@endsection