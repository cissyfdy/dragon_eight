@extends('layout')

@section('konten')
    <title>Edit Murid</title>

    <div class="container-fluid py-4">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <!-- Header -->
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-warning text-dark">
                        <h4 class="mb-0">
                            <i class="bi bi-pencil-square me-2"></i>
                            Edit Data Murid
                        </h4>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('murid.update', ['id' => $murid->murid_id]) }}" method="POST">
                            @csrf
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="murid_id" class="form-label">Murid ID <span class="text-danger">*</span></label>
                                        <input type="text" name="murid_id" id="murid_id" class="form-control" 
                                               value="{{ old('murid_id', $murid->murid_id) }}" readonly>
                                        @error('murid_id')
                                            <div class="text-danger small mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="nama_murid" class="form-label">Nama Murid <span class="text-danger">*</span></label>
                                        <input type="text" name="nama_murid" id="nama_murid" class="form-control" 
                                               value="{{ old('nama_murid', $murid->nama_murid) }}" required>
                                        @error('nama_murid')
                                            <div class="text-danger small mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="nomor_register" class="form-label">No Register <span class="text-danger">*</span></label>
                                        <input type="text" name="nomor_register" id="nomor_register" class="form-control" 
                                               value="{{ old('nomor_register', $murid->nomor_register) }}" required>
                                        @error('nomor_register')
                                            <div class="text-danger small mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="tanggal_lahir" class="form-label">Tanggal Lahir <span class="text-danger">*</span></label>
                                        <input type="date" name="tanggal_lahir" id="tanggal_lahir" class="form-control" 
                                               value="{{ old('tanggal_lahir', $murid->tanggal_lahir) }}" required>
                                        @error('tanggal_lahir')
                                            <div class="text-danger small mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="tingkat_sabuk" class="form-label">Tingkat Sabuk <span class="text-danger">*</span></label>
                                        <input type="text" name="tingkat_sabuk" id="tingkat_sabuk" class="form-control" 
                                               value="{{ old('tingkat_sabuk', $murid->tingkat_sabuk) }}" required>
                                        @error('tingkat_sabuk')
                                            <div class="text-danger small mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="no_hp" class="form-label">No. HP <span class="text-danger">*</span></label>
                                        <input type="text" name="no_hp" id="no_hp" class="form-control" 
                                               value="{{ old('no_hp', $murid->no_hp) }}" required>
                                        @error('no_hp')
                                            <div class="text-danger small mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-12">
                                    <div class="mb-3">
                                        <label for="alamat" class="form-label">Alamat <span class="text-danger">*</span></label>
                                        <textarea name="alamat" id="alamat" class="form-control" rows="3" required>{{ old('alamat', $murid->alamat) }}</textarea>
                                        @error('alamat')
                                            <div class="text-danger small mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Status info -->
                            <div class="alert alert-info">
                                <i class="bi bi-info-circle me-2"></i>
                                <strong>Info:</strong> 
                                Data murid akan diperbarui setelah tombol Update diklik.
                            </div>

                            <!-- Tombol aksi -->
                            <div class="d-flex justify-content-between">
                                <a href="{{ route('admin.tampilMurid') }}" class="btn btn-secondary">
                                    <i class="bi bi-arrow-left me-2"></i>
                                    Kembali
                                </a>
                                <div>
                                    <button type="reset" class="btn btn-outline-secondary me-2" onclick="resetForm()">
                                        <i class="bi bi-arrow-clockwise me-2"></i>
                                        Reset
                                    </button>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="bi bi-check-circle me-2"></i>
                                        Update Murid
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
        // Reset form to original values
        function resetForm() {
            document.getElementById('murid_id').value = '{{ $murid->murid_id }}';
            document.getElementById('nama_murid').value = '{{ $murid->nama_murid }}';
            document.getElementById('nomor_register').value = '{{ $murid->nomor_register }}';
            document.getElementById('tanggal_lahir').value = '{{ $murid->tanggal_lahir }}';
            document.getElementById('tingkat_sabuk').value = '{{ $murid->tingkat_sabuk }}';
            document.getElementById('no_hp').value = '{{ $murid->no_hp }}';
            document.getElementById('alamat').value = '{{ $murid->alamat }}';
        }

        // Format phone number input
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