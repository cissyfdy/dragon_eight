@extends('layout')

@section('konten')
    <title>Edit Unit</title>

    <div class="container-fluid py-4">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <!-- Header -->
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-warning text-dark">
                        <h4 class="mb-0">
                            <i class="bi bi-pencil-square me-2"></i>
                            Edit Data Unit
                        </h4>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('unit.update', ['id' => $unit->unit_id]) }}" method="POST">
                            @csrf
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="unit_id" class="form-label">Unit ID <span class="text-danger">*</span></label>
                                        <input type="text" name="unit_id" id="unit_id" class="form-control" 
                                               value="{{ old('unit_id', $unit->unit_id) }}" readonly>
                                        @error('unit_id')
                                            <div class="text-danger small mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="nama_unit" class="form-label">Nama Unit <span class="text-danger">*</span></label>
                                        <input type="text" name="nama_unit" id="nama_unit" class="form-control" 
                                               value="{{ old('nama_unit', $unit->nama_unit) }}" required>
                                        @error('nama_unit')
                                            <div class="text-danger small mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-12">
                                    <div class="mb-3">
                                        <label for="alamat" class="form-label">Alamat <span class="text-danger">*</span></label>
                                        <textarea name="alamat" id="alamat" class="form-control" rows="3" required>{{ old('alamat', $unit->alamat) }}</textarea>
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
                                Data unit akan diperbarui setelah tombol Update diklik.
                            </div>

                            <!-- Tombol aksi -->
                            <div class="d-flex justify-content-between">
                                <a href="{{ route('admin.tampilUnit') }}" class="btn btn-secondary">
                                    <i class="bi bi-arrow-left me-2"></i>
                                    Kembali
                                </a>
                                <div>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="bi bi-check-circle me-2"></i>
                                        Update Unit
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