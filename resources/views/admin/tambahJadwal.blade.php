@extends('layout')

@section('konten')
    <title>Tambah Jadwal</title>

    <div class="container-fluid py-4">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <!-- Header -->
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0">
                            <i class="bi bi-calendar-plus me-2"></i>
                            Tambah Jadwal Baru
                        </h4>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('jadwal.store') }}" method="POST" id="jadwalForm">
                            @csrf
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="hari" class="form-label">Hari <span class="text-danger">*</span></label>
                                        <select name="hari" id="hari" class="form-select" required>
                                            <option value="">-- Pilih Hari --</option>
                                            <option value="Senin" {{ old('hari') == 'Senin' ? 'selected' : '' }}>Senin</option>
                                            <option value="Selasa" {{ old('hari') == 'Selasa' ? 'selected' : '' }}>Selasa</option>
                                            <option value="Rabu" {{ old('hari') == 'Rabu' ? 'selected' : '' }}>Rabu</option>
                                            <option value="Kamis" {{ old('hari') == 'Kamis' ? 'selected' : '' }}>Kamis</option>
                                            <option value="Jumat" {{ old('hari') == 'Jumat' ? 'selected' : '' }}>Jumat</option>
                                            <option value="Sabtu" {{ old('hari') == 'Sabtu' ? 'selected' : '' }}>Sabtu</option>
                                            <option value="Minggu" {{ old('hari') == 'Minggu' ? 'selected' : '' }}>Minggu</option>
                                        </select>
                                        @error('hari')
                                            <div class="text-danger small mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                                        <select name="status" id="status" class="form-select" required>
                                            <option value="">-- Pilih Status --</option>
                                            <option value="aktif" {{ old('status') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                                            <option value="tidak_aktif" {{ old('status') == 'tidak_aktif' ? 'selected' : '' }}>Tidak Aktif</option>
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
                                               value="{{ old('jam_mulai') }}" required>
                                        @error('jam_mulai')
                                            <div class="text-danger small mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="jam_selesai" class="form-label">Jam Selesai <span class="text-danger">*</span></label>
                                        <input type="time" name="jam_selesai" id="jam_selesai" class="form-control" 
                                               value="{{ old('jam_selesai') }}" required>
                                        @error('jam_selesai')
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
                                        <label for="pelatih_id" class="form-label">Pelatih <span class="text-danger">*</span></label>
                                        <select name="pelatih_id" id="pelatih_id" class="form-select" required>
                                            <option value="">-- Pilih Pelatih --</option>
                                            @foreach($pelatih as $p)
                                                <option value="{{ $p->pelatih_id }}" {{ old('pelatih_id') == $p->pelatih_id ? 'selected' : '' }}>
                                                    {{ $p->nama_pelatih }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('pelatih_id')
                                            <div class="text-danger small mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="keterangan" class="form-label">Keterangan</label>
                                <textarea name="keterangan" id="keterangan" class="form-control" rows="3" 
                                          placeholder="Masukkan keterangan jadwal (opsional)">{{ old('keterangan') }}</textarea>
                                @error('keterangan')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Informasi tambahan -->
                            <div class="alert alert-info">
                                <i class="bi bi-info-circle me-2"></i>
                                <strong>Catatan:</strong> Pastikan tidak ada jadwal yang bentrok dengan pelatih yang sama pada hari dan waktu yang dipilih.
                            </div>

                            <!-- Tombol aksi -->
                            <div class="d-flex justify-content-between">
                                <a href="{{ route('admin.tampilJadwal') }}" class="btn btn-secondary">
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
                                        Simpan Jadwal
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
        // Validasi jam selesai harus lebih besar dari jam mulai
        document.getElementById('jadwalForm').addEventListener('submit', function(e) {
            const jamMulai = document.getElementById('jam_mulai').value;
            const jamSelesai = document.getElementById('jam_selesai').value;
            
            if (jamMulai && jamSelesai && jamSelesai <= jamMulai) {
                e.preventDefault();
                alert('Jam selesai harus lebih besar dari jam mulai!');
                return false;
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