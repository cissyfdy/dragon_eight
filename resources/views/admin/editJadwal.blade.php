@extends('layout')

@section('konten')
    <title>Edit Jadwal</title>

    <div class="container-fluid py-4">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <!-- Header -->
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-warning text-dark">
                        <h4 class="mb-0">
                            <i class="bi bi-pencil-square me-2"></i>
                            Edit Jadwal
                        </h4>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('jadwal.update', $jadwal->jadwal_id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="hari" class="form-label">Hari <span class="text-danger">*</span></label>
                                        <select name="hari" id="hari" class="form-select" required>
                                            <option value="">-- Pilih Hari --</option>
                                            @php
                                                $hariList = ['Senin','Selasa','Rabu','Kamis','Jumat','Sabtu','Minggu'];
                                            @endphp
                                            @foreach($hariList as $hari)
                                                <option value="{{ $hari }}" 
                                                    {{ (old('hari', $jadwal->hari) == $hari) ? 'selected' : '' }}>
                                                    {{ $hari }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('hari')
                                            <div class="text-danger small mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="unit_id" class="form-label">Unit <span class="text-danger">*</span></label>
                                        <select name="unit_id" id="unit_id" class="form-select" required>
                                            <option value="">-- Pilih Unit --</option>
                                            @foreach($units as $unit)
                                                <option value="{{ $unit->unit_id }}" 
                                                    {{ (old('unit_id', $jadwal->unit_id) == $unit->unit_id) ? 'selected' : '' }}>
                                                    {{ $unit->nama_unit }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('unit_id')
                                            <div class="text-danger small mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="pelatih_id" class="form-label">Pelatih <span class="text-danger">*</span></label>
                                        <select name="pelatih_id" id="pelatih_id" class="form-select" required>
                                            <option value="">-- Pilih Pelatih --</option>
                                            @foreach($pelatih as $p)
                                                <option value="{{ $p->pelatih_id }}" 
                                                    {{ (old('pelatih_id', $jadwal->pelatih_id) == $p->pelatih_id) ? 'selected' : '' }}>
                                                    {{ $p->nama_pelatih }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('pelatih_id')
                                            <div class="text-danger small mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                                        <select name="status" id="status" class="form-select" required>
                                            <option value="">-- Pilih Status --</option>
                                            <option value="aktif" {{ (old('status', $jadwal->status) == 'aktif') ? 'selected' : '' }}>
                                                Aktif
                                            </option>
                                            <option value="tidak_aktif" {{ (old('status', $jadwal->status) == 'tidak_aktif') ? 'selected' : '' }}>
                                                Tidak Aktif
                                            </option>
                                        </select>
                                        @error('status')
                                            <div class="text-danger small mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="jam_mulai" class="form-label">Jam Mulai <span class="text-danger">*</span></label>
                                        <input type="time" name="jam_mulai" id="jam_mulai" class="form-control" 
                                               value="{{ old('jam_mulai', $jadwal->jam_mulai) }}" required>
                                        @error('jam_mulai')
                                            <div class="text-danger small mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="jam_selesai" class="form-label">Jam Selesai <span class="text-danger">*</span></label>
                                        <input type="time" name="jam_selesai" id="jam_selesai" class="form-control" 
                                               value="{{ old('jam_selesai', $jadwal->jam_selesai) }}" required>
                                        @error('jam_selesai')
                                            <div class="text-danger small mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label for="keterangan" class="form-label">Keterangan</label>
                                        <textarea name="keterangan" id="keterangan" class="form-control" rows="3" 
                                                  placeholder="Masukkan keterangan tambahan (opsional)">{{ old('keterangan', $jadwal->keterangan) }}</textarea>
                                        @error('keterangan')
                                            <div class="text-danger small mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Status info -->
                            <div class="alert alert-info">
                                <i class="bi bi-info-circle me-2"></i>
                                <strong>Status saat ini:</strong> 
                                @if($jadwal->status == 'aktif')
                                    <span class="badge bg-success">Aktif</span>
                                @else
                                    <span class="badge bg-secondary">Tidak Aktif</span>
                                @endif
                                <br>
                                <strong>Jumlah Murid Terdaftar:</strong> {{ $jadwal->jumlah_murid ?? 0 }} murid
                            </div>

                            <!-- Tombol aksi -->
                            <div class="d-flex justify-content-between">
                                <a href="{{ route('admin.tampilJadwal') }}" class="btn btn-secondary">
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
                                        Update Jadwal
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
        // Validasi jam mulai dan jam selesai
        document.getElementById('jam_selesai').addEventListener('change', function() {
            const jamMulai = document.getElementById('jam_mulai').value;
            const jamSelesai = this.value;
            
            if (jamMulai && jamSelesai) {
                if (jamSelesai <= jamMulai) {
                    alert('Jam selesai harus lebih besar dari jam mulai!');
                    this.value = '';
                }
            }
        });

        document.getElementById('jam_mulai').addEventListener('change', function() {
            const jamMulai = this.value;
            const jamSelesai = document.getElementById('jam_selesai').value;
            
            if (jamMulai && jamSelesai) {
                if (jamSelesai <= jamMulai) {
                    alert('Jam mulai harus lebih kecil dari jam selesai!');
                    document.getElementById('jam_selesai').value = '';
                }
            }
        });

        // Reset form to original values
        function resetForm() {
            // Reset ke nilai database
            document.getElementById('hari').value = '{{ $jadwal->hari }}';
            document.getElementById('unit_id').value = '{{ $jadwal->unit_id }}';
            document.getElementById('pelatih_id').value = '{{ $jadwal->pelatih_id }}';
            document.getElementById('status').value = '{{ $jadwal->status }}';
            document.getElementById('jam_mulai').value = '{{ $jadwal->jam_mulai }}';
            document.getElementById('jam_selesai').value = '{{ $jadwal->jam_selesai }}';
            document.getElementById('keterangan').value = '{{ $jadwal->keterangan }}';
        }

        // Check for conflicting schedules
        function checkConflict() {
            const hari = document.getElementById('hari').value;
            const jamMulai = document.getElementById('jam_mulai').value;
            const jamSelesai = document.getElementById('jam_selesai').value;
            const pelatihId = document.getElementById('pelatih_id').value;
            const unitId = document.getElementById('unit_id').value;
            
            if (hari && jamMulai && jamSelesai && (pelatihId || unitId)) {
                // Ajax call to check conflicts (implement in controller)
                fetch('/api/jadwal/check-conflict', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        hari: hari,
                        jam_mulai: jamMulai,
                        jam_selesai: jamSelesai,
                        pelatih_id: pelatihId,
                        unit_id: unitId,
                        exclude_jadwal_id: {{ $jadwal->jadwal_id }}
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.conflict) {
                        alert('Terdapat konflik jadwal: ' + data.message);
                    }
                });
            }
        }

        // Add event listeners for conflict checking
        ['hari', 'jam_mulai', 'jam_selesai', 'pelatih_id', 'unit_id'].forEach(id => {
            document.getElementById(id).addEventListener('change', checkConflict);
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