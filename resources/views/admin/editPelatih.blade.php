@extends('layout')

@section('konten')
    <title>Edit Pelatih</title>

    <div class="container-fluid py-4">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <!-- Header -->
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-success text-white">
                        <h4 class="mb-0">
                            <i class="bi bi-pencil-square me-2"></i>
                            Edit Data Pelatih
                        </h4>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('pelatih.update', ['id' => $pelatih->pelatih_id]) }}" method="POST">
                            @csrf
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="pelatih_id" class="form-label">Pelatih ID <span class="text-danger">*</span></label>
                                        <input type="text" name="pelatih_id" id="pelatih_id" class="form-control" 
                                               value="{{ old('pelatih_id', $pelatih->pelatih_id) }}" readonly>
                                        @error('pelatih_id')
                                            <div class="text-danger small mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="nama_pelatih" class="form-label">Nama Pelatih <span class="text-danger">*</span></label>
                                        <input type="text" name="nama_pelatih" id="nama_pelatih" class="form-control" 
                                               value="{{ old('nama_pelatih', $pelatih->nama_pelatih) }}" required>
                                        @error('nama_pelatih')
                                            <div class="text-danger small mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="no_hp" class="form-label">No. HP <span class="text-danger">*</span></label>
                                        <input type="text" name="no_hp" id="no_hp" class="form-control" 
                                               value="{{ old('no_hp', $pelatih->no_hp) }}" required>
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
                                        <textarea name="alamat" id="alamat" class="form-control" rows="3" required>{{ old('alamat', $pelatih->alamat) }}</textarea>
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
                                Data pelatih akan diperbarui setelah tombol Update diklik.
                            </div>

                            <!-- Tombol aksi -->
                            <div class="d-flex justify-content-between">
                                <a href="{{ route('admin.tampilPelatih') }}" class="btn btn-secondary">
                                    <i class="bi bi-arrow-left me-2"></i>
                                    Kembali
                                </a>
                                <div>
                                    <button type="reset" class="btn btn-outline-secondary me-2" onclick="resetForm()">
                                        <i class="bi bi-arrow-clockwise me-2"></i>
                                        Reset
                                    </button>
                                    <button type="submit" class="btn btn-success">
                                        <i class="bi bi-check-circle me-2"></i>
                                        Update Pelatih
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
            document.getElementById('pelatih_id').value = '{{ $pelatih->pelatih_id }}';
            document.getElementById('nama_pelatih').value = '{{ $pelatih->nama_pelatih }}';
            document.getElementById('no_hp').value = '{{ $pelatih->no_hp }}';
            document.getElementById('alamat').value = '{{ $pelatih->alamat }}';
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