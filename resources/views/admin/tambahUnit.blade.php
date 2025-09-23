@extends('layout')

@section('konten')
    <title>Tambah Unit</title>

    <div class="container-fluid py-4">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <!-- Header -->
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0">
                            <i class="bi bi-calendar-plus me-3"></i>
                            Tambah Unit Baru
                        </h4>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('unit.add') }}" method="POST">
                            @csrf
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="no" class="form-label">No <span class="text-danger">*</span></label>
                                        <input type="number" name="no" id="no" class="form-control" 
                                               placeholder="Masukkan nomor urut" value="{{ old('no') }}" required>
                                        @error('no')
                                            <div class="text-danger small mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="nama_unit" class="form-label">Nama Unit <span class="text-danger">*</span></label>
                                        <input type="text" name="nama_unit" id="nama_unit" class="form-control" 
                                               placeholder="Masukkan nama unit" value="{{ old('nama_unit') }}" required>
                                        @error('nama_unit')
                                            <div class="text-danger small mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="alamat" class="form-label">Alamat <span class="text-danger">*</span></label>
                                <textarea name="alamat" id="alamat" class="form-control" rows="3" 
                                          placeholder="Masukkan alamat lengkap unit" required>{{ old('alamat') }}</textarea>
                                @error('alamat')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Informasi tambahan -->
                            <div class="alert alert-info">
                                <i class="bi bi-info-circle me-2"></i>
                                <strong>Catatan:</strong> Unit yang ditambahkan akan digunakan untuk mengelompokkan murid dan jadwal latihan.
                            </div>

                            <!-- Tombol aksi -->
                            <div class="d-flex justify-content-between">
                                <a href="{{ route('admin.tampilUnit') }}" class="btn btn-secondary">
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
                                        Simpan Unit
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
        // Show validation errors
        @if($errors->any())
            setTimeout(function() {
                let errors = @json($errors->all());
                alert('Terdapat kesalahan:\n' + errors.join('\n'));
            }, 100);
        @endif
    </script>
@endsection