@extends('layout')

@section('konten')
    <title>Tambah Ujian Kenaikan Tingkat</title>

    <div class="container-fluid py-4">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <!-- Header -->
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0">
                            <i class="bi bi-plus-circle me-2"></i>
                            Tambah Ujian Kenaikan Tingkat
                        </h4>
                        <small class="opacity-75">Buat ujian kenaikan tingkat sabuk baru</small>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.storeUjian') }}" method="POST">
                            @csrf
                            
                            <!-- Informasi Dasar Ujian -->
                            <div class="mb-4">
                                <h5 class="text-primary mb-3">
                                    <i class="bi bi-info-circle me-2"></i>
                                    Informasi Dasar Ujian
                                </h5>
                                
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <label for="nama_ujian" class="form-label">Nama Ujian <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control @error('nama_ujian') is-invalid @enderror" 
                                                   id="nama_ujian" name="nama_ujian" value="{{ old('nama_ujian') }}" 
                                                   placeholder="Contoh: Ujian Kenaikan Sabuk Kuning ke Hijau" required>
                                            @error('nama_ujian')
                                                <div class="text-danger small mt-1">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="tanggal_ujian" class="form-label">Tanggal Ujian <span class="text-danger">*</span></label>
                                            <input type="date" class="form-control @error('tanggal_ujian') is-invalid @enderror" 
                                                   id="tanggal_ujian" name="tanggal_ujian" value="{{ old('tanggal_ujian') }}" 
                                                   min="{{ date('Y-m-d', strtotime('+1 day')) }}" required>
                                            @error('tanggal_ujian')
                                                <div class="text-danger small mt-1">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="waktu_mulai" class="form-label">Jam Mulai <span class="text-danger">*</span></label>
                                            <input type="time" class="form-control @error('waktu_mulai') is-invalid @enderror" 
                                                   id="waktu_mulai" name="waktu_mulai" value="{{ old('waktu_mulai', '08:00') }}" required>
                                            @error('waktu_mulai')
                                                <div class="text-danger small mt-1">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="waktu_selesai" class="form-label">Jam Selesai <span class="text-danger">*</span></label>
                                            <input type="time" class="form-control @error('waktu_selesai') is-invalid @enderror" 
                                                   id="waktu_selesai" name="waktu_selesai" value="{{ old('waktu_selesai', '12:00') }}" required>
                                            @error('waktu_selesai')
                                                <div class="text-danger small mt-1">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="unit_id" class="form-label">Unit <span class="text-danger">*</span></label>
                                            <select class="form-select @error('unit_id') is-invalid @enderror" id="unit_id" name="unit_id" required>
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
                                            <label for="pelatih_id" class="form-label">Pelatih Penguji <span class="text-danger">*</span></label>
                                            <select class="form-select @error('pelatih_id') is-invalid @enderror" id="pelatih_id" name="pelatih_id" required>
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
                            </div>

                            <!-- Tingkat Sabuk dan Biaya -->
                            <div class="mb-4">
                                <h5 class="text-primary mb-3">
                                    <i class="bi bi-award me-2"></i>
                                    Tingkat Sabuk & Biaya
                                </h5>
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="sabuk_dari" class="form-label">Sabuk Dari <span class="text-danger">*</span></label>
                                            <select class="form-select @error('sabuk_dari') is-invalid @enderror" id="sabuk_dari" name="sabuk_dari" required>
                                                <option value="">-- Pilih Tingkat Asal --</option>
                                                <option value="Sabuk Putih" {{ old('sabuk_dari') == 'Sabuk Putih' ? 'selected' : '' }}>Sabuk Putih</option>
                                                <option value="Sabuk Kuning" {{ old('sabuk_dari') == 'Sabuk Kuning' ? 'selected' : '' }}>Sabuk Kuning</option>
                                                <option value="Sabuk Hijau" {{ old('sabuk_dari') == 'Sabuk Hijau' ? 'selected' : '' }}>Sabuk Hijau</option>
                                                <option value="Sabuk Biru" {{ old('sabuk_dari') == 'Sabuk Biru' ? 'selected' : '' }}>Sabuk Biru</option>
                                                <option value="Sabuk Coklat" {{ old('sabuk_dari') == 'Sabuk Coklat' ? 'selected' : '' }}>Sabuk Coklat</option>
                                            </select>
                                            @error('sabuk_dari')
                                                <div class="text-danger small mt-1">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="sabuk_ke" class="form-label">Sabuk Ke <span class="text-danger">*</span></label>
                                            <select class="form-select @error('sabuk_ke') is-invalid @enderror" id="sabuk_ke" name="sabuk_ke" required>
                                                <option value="">-- Pilih Tingkat Tujuan --</option>
                                                <option value="Sabuk Kuning" {{ old('sabuk_ke') == 'Sabuk Kuning' ? 'selected' : '' }}>Sabuk Kuning</option>
                                                <option value="Sabuk Hijau" {{ old('sabuk_ke') == 'Sabuk Hijau' ? 'selected' : '' }}>Sabuk Hijau</option>
                                                <option value="Sabuk Biru" {{ old('sabuk_ke') == 'Sabuk Biru' ? 'selected' : '' }}>Sabuk Biru</option>
                                                <option value="Sabuk Coklat" {{ old('sabuk_ke') == 'Sabuk Coklat' ? 'selected' : '' }}>Sabuk Coklat</option>
                                                <option value="Sabuk Hitam" {{ old('sabuk_ke') == 'Sabuk Hitam' ? 'selected' : '' }}>Sabuk Hitam</option>
                                            </select>
                                            @error('sabuk_ke')
                                                <div class="text-danger small mt-1">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="biaya_ujian" class="form-label">Biaya Ujian <span class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <span class="input-group-text">Rp</span>
                                                <input type="number" class="form-control @error('biaya_ujian') is-invalid @enderror" 
                                                       id="biaya_ujian" name="biaya_ujian" value="{{ old('biaya_ujian', 100000) }}" 
                                                       min="0" step="1000" placeholder="100000" required>
                                            </div>
                                            @error('biaya_ujian')
                                                <div class="text-danger small mt-1">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="kuota_peserta" class="form-label">Kuota Peserta <span class="text-danger">*</span></label>
                                            <input type="number" class="form-control @error('kuota_peserta') is-invalid @enderror" 
                                                   id="kuota_peserta" name="kuota_peserta" value="{{ old('kuota_peserta', 20) }}" 
                                                   min="1" max="100" placeholder="20" required>
                                            @error('kuota_peserta')
                                                <div class="text-danger small mt-1">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="persyaratan" class="form-label">Persyaratan Ujian</label>
                                    <textarea class="form-control @error('persyaratan') is-invalid @enderror" 
                                              id="persyaratan" name="persyaratan" rows="4" 
                                              placeholder="Contoh: Minimal latihan 6 bulan, menguasai jurus dasar 1-10, dll.">{{ old('persyaratan') }}</textarea>
                                    @error('persyaratan')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Keterangan Tambahan -->
                            <div class="mb-4">
                                <h5 class="text-primary mb-3">
                                    <i class="bi bi-chat-left-text me-2"></i>
                                    Keterangan Tambahan
                                </h5>
                                
                                <div class="mb-3">
                                    <label for="keterangan" class="form-label">Keterangan</label>
                                    <textarea class="form-control @error('keterangan') is-invalid @enderror" 
                                              id="keterangan" name="keterangan" rows="3" 
                                              placeholder="Keterangan tambahan mengenai ujian (opsional)">{{ old('keterangan') }}</textarea>
                                    @error('keterangan')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Informasi tambahan -->
                            <div class="alert alert-info">
                                <i class="bi bi-info-circle me-2"></i>
                                <strong>Catatan:</strong> Status ujian akan otomatis diset sebagai "Dijadwalkan" dan dapat diubah nanti sesuai kebutuhan.
                            </div>

                            <!-- Tombol aksi -->
                            <div class="d-flex justify-content-between">
                                <a href="{{ route('admin.tampilUjian') }}" class="btn btn-secondary">
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
                                        Simpan Ujian
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
        // Auto-populate nama ujian berdasarkan sabuk
        document.getElementById('sabuk_dari').addEventListener('change', updateNamaUjian);
        document.getElementById('sabuk_ke').addEventListener('change', updateNamaUjian);

        function updateNamaUjian() {
            const sabukDari = document.getElementById('sabuk_dari').value;
            const sabukKe = document.getElementById('sabuk_ke').value;
            const namaUjianField = document.getElementById('nama_ujian');
            
            if (sabukDari && sabukKe && namaUjianField.value === '') {
                namaUjianField.value = `Ujian Kenaikan ${sabukDari} ke ${sabukKe}`;
            }
        }

        // Auto-update biaya berdasarkan tingkat sabuk
        document.getElementById('sabuk_ke').addEventListener('change', function() {
            const sabukKe = this.value;
            const biayaField = document.getElementById('biaya_ujian');
            
            // Set biaya default berdasarkan tingkat sabuk tujuan
            const biayaDefault = {
                'Sabuk Kuning': 100000,
                'Sabuk Hijau': 150000,
                'Sabuk Biru': 200000,
                'Sabuk Coklat': 250000,
                'Sabuk Hitam': 500000
            };
            
            if (sabukKe && biayaDefault[sabukKe] && biayaField.value == 100000) {
                biayaField.value = biayaDefault[sabukKe];
            }
        });

        // Validasi waktu
        document.getElementById('waktu_mulai').addEventListener('change', function() {
            const waktuMulai = this.value;
            const waktuSelesaiField = document.getElementById('waktu_selesai');
            
            if (waktuMulai && !waktuSelesaiField.value) {
                // Set waktu selesai 4 jam setelah mulai
                const [hours, minutes] = waktuMulai.split(':');
                const endHours = parseInt(hours) + 4;
                waktuSelesaiField.value = `${endHours.toString().padStart(2, '0')}:${minutes}`;
            }
        });

        // Format input biaya
        document.getElementById('biaya_ujian').addEventListener('input', function() {
            let value = this.value.replace(/[^\d]/g, '');
            this.value = value;
        });

        // Prevent form submission jika waktu tidak valid
        document.querySelector('form').addEventListener('submit', function(e) {
            const waktuMulai = document.getElementById('waktu_mulai').value;
            const waktuSelesai = document.getElementById('waktu_selesai').value;
            
            if (waktuMulai && waktuSelesai && waktuMulai >= waktuSelesai) {
                e.preventDefault();
                alert('Waktu selesai harus lebih besar dari waktu mulai!');
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