@extends('layout')

@section('konten')
    <title>Edit Iuran</title>

    <div class="container-fluid py-4">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <!-- Header -->
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-warning text-dark">
                        <h4 class="mb-0">
                            <i class="bi bi-pencil-square me-2"></i>
                            Edit Iuran
                        </h4>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('iuran.update', $iuran->iuran_id) }}" method="POST">
                            @csrf
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="murid_id" class="form-label">Pilih Murid <span class="text-danger">*</span></label>
                                        <select name="murid_id" id="murid_id" class="form-select" required>
                                            <option value="">-- Pilih Murid --</option>
                                            @foreach($murid as $m)
                                                <option value="{{ $m->murid_id }}" 
                                                    {{ (old('murid_id', $iuran->murid_id) == $m->murid_id) ? 'selected' : '' }}>
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
                                                <option value="{{ $bulan }}" 
                                                    {{ (old('bulan', $iuran->bulan) == $bulan) ? 'selected' : '' }}>
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
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="tahun" class="form-label">Tahun <span class="text-danger">*</span></label>
                                        <input type="number" name="tahun" id="tahun" class="form-control" 
                                               min="2020" max="2030" value="{{ old('tahun', $iuran->tahun) }}" required>
                                        @error('tahun')
                                            <div class="text-danger small mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="nominal" class="form-label">Nominal <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <span class="input-group-text">Rp</span>
                                            <input type="number" name="nominal" id="nominal" class="form-control" 
                                                   min="0" value="{{ old('nominal', $iuran->nominal) }}" required>
                                        </div>
                                        @error('nominal')
                                            <div class="text-danger small mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                                        <select name="status" id="status" class="form-select" required>
                                            <option value="">-- Pilih Status --</option>
                                            <option value="Lunas" {{ (old('status', $iuran->status) == 'Lunas') ? 'selected' : '' }}>
                                                Lunas
                                            </option>
                                            <option value="Belum Lunas" {{ (old('status', $iuran->status) == 'Belum Lunas') ? 'selected' : '' }}>
                                                Belum Lunas
                                            </option>
                                        </select>
                                        @error('status')
                                            <div class="text-danger small mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Tanggal bayar (conditional) -->
                            <div class="row" id="tanggal-bayar-row" style="{{ $iuran->status == 'Lunas' ? '' : 'display:none;' }}">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="tanggal_bayar" class="form-label">Tanggal Pembayaran</label>
                                        <input type="date" name="tanggal_bayar" id="tanggal_bayar" class="form-control" 
                                               value="{{ old('tanggal_bayar', $iuran->tanggal_bayar) }}">
                                        <small class="form-text text-muted">Kosongkan jika belum dibayar</small>
                                    </div>
                                </div>
                            </div>

                            <!-- Status info -->
                            <div class="alert alert-info">
                                <i class="bi bi-info-circle me-2"></i>
                                <strong>Status saat ini:</strong> 
                                @if($iuran->status == 'Lunas')
                                    <span class="badge bg-success">Lunas</span>
                                    @if($iuran->tanggal_bayar)
                                        - Dibayar tanggal {{ date('d/m/Y', strtotime($iuran->tanggal_bayar)) }}
                                    @endif
                                @else
                                    <span class="badge bg-danger">Belum Lunas</span>
                                @endif
                            </div>

                            <!-- Tombol aksi -->
                            <div class="d-flex justify-content-between">
                                <a href="{{ route('admin.tampilIuran') }}" class="btn btn-secondary">
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
                                        Update Iuran
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
        // Show/hide tanggal bayar based on status
        document.getElementById('status').addEventListener('change', function() {
            const tanggalBayarRow = document.getElementById('tanggal-bayar-row');
            const tanggalBayarInput = document.getElementById('tanggal_bayar');
            
            if (this.value === 'Lunas') {
                tanggalBayarRow.style.display = '';
                if (!tanggalBayarInput.value) {
                    tanggalBayarInput.value = new Date().toISOString().split('T')[0];
                }
            } else {
                tanggalBayarRow.style.display = 'none';
                tanggalBayarInput.value = '';
            }
        });

        // Reset form to original values
        function resetForm() {
            // Reset ke nilai database
            document.getElementById('murid_id').value = '{{ $iuran->murid_id }}';
            document.getElementById('bulan').value = '{{ $iuran->bulan }}';
            document.getElementById('tahun').value = '{{ $iuran->tahun }}';
            document.getElementById('nominal').value = '{{ $iuran->nominal }}';
            document.getElementById('status').value = '{{ $iuran->status }}';
            document.getElementById('tanggal_bayar').value = '{{ $iuran->tanggal_bayar }}';
            
            // Trigger status change event
            document.getElementById('status').dispatchEvent(new Event('change'));
        }

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