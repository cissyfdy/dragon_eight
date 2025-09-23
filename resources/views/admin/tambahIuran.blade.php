@extends('layout')

@section('konten')
    <title>Tambah Iuran</title>

    <div class="container-fluid py-4">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <!-- Header -->
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0">
                            <i class="bi bi-plus-circle me-2"></i>
                            Tambah Iuran Baru
                        </h4>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('iuran.add') }}" method="POST">
                            @csrf
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="murid_id" class="form-label">Pilih Murid <span class="text-danger">*</span></label>
                                        <select name="murid_id" id="murid_id" class="form-select" required>
                                            <option value="">-- Pilih Murid --</option>
                                            @foreach($murid as $m)
                                                <option value="{{ $m->murid_id }}" {{ old('murid_id') == $m->murid_id ? 'selected' : '' }}>
                                                    {{ $m->nama_murid }} - {{ $m->nama_unit }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('murid_id')
                                            <div class="text-danger small mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="bulan" class="form-label">Bulan <span class="text-danger">*</span></label>
                                        <select name="bulan" id="bulan" class="form-select" required>
                                            <option value="">-- Pilih Bulan --</option>
                                            @php
                                                $bulanList = ['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
                                            @endphp
                                            @foreach($bulanList as $bulan)
                                                <option value="{{ $bulan }}" {{ old('bulan') == $bulan ? 'selected' : '' }}>
                                                    {{ $bulan }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('bulan')
                                            <div class="text-danger small mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="tahun" class="form-label">Tahun <span class="text-danger">*</span></label>
                                        <input type="number" name="tahun" id="tahun" class="form-control" 
                                               min="2020" max="2030" value="{{ old('tahun', date('Y')) }}" required>
                                        @error('tahun')
                                            <div class="text-danger small mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="nominal" class="form-label">Nominal <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <span class="input-group-text">Rp</span>
                                            <input type="number" name="nominal" id="nominal" class="form-control" 
                                                   min="0" value="{{ old('nominal', 150000) }}" required>
                                        </div>
                                        @error('nominal')
                                            <div class="text-danger small mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Informasi tambahan -->
                            <div class="alert alert-info">
                                <i class="bi bi-info-circle me-2"></i>
                                <strong>Catatan:</strong> Status iuran akan otomatis diset sebagai "Belum Lunas" dan dapat diubah nanti saat konfirmasi pembayaran.
                            </div>

                            <!-- Tombol aksi -->
                            <div class="d-flex justify-content-between">
                                <a href="{{ route('admin.tampilIuran') }}" class="btn btn-secondary">
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
                                        Simpan Iuran
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
        // Format nominal input
        document.getElementById('nominal').addEventListener('input', function() {
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