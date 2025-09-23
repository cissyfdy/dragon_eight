@extends('layout')

@section('konten')
    <title>Edit Ujian Kenaikan Tingkat</title>

    <div class="container-fluid py-4">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <!-- Header -->
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-warning text-dark">
                        <h4 class="mb-0">
                            <i class="bi bi-pencil-square me-2"></i>
                            Edit Ujian Kenaikan Tingkat
                        </h4>
                        <small class="opacity-75">Edit informasi ujian: {{ $ujian->nama_ujian }}</small>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.updateUjian', $ujian->ujian_id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            
                            <!-- Informasi Dasar Ujian -->
                            <div class="mb-4">
                                <h5 class="text-warning mb-3">
                                    <i class="bi bi-info-circle me-2"></i>
                                    Informasi Dasar Ujian
                                </h5>
                                
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <label for="nama_ujian" class="form-label">Nama Ujian <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control @error('nama_ujian') is-invalid @enderror" 
                                                   id="nama_ujian" name="nama_ujian" value="{{ old('nama_ujian', $ujian->nama_ujian) }}" 
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
                                                   id="tanggal_ujian" name="tanggal_ujian" 
                                                   value="{{ old('tanggal_ujian', $ujian->tanggal_ujian->format('Y-m-d')) }}" required>
                                            @error('tanggal_ujian')
                                                <div class="text-danger small mt-1">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="waktu_mulai" class="form-label">Jam Mulai <span class="text-danger">*</span></label>
                                            <input type="time" class="form-control @error('waktu_mulai') is-invalid @enderror" 
                                                   id="waktu_mulai" name="waktu_mulai" 
                                                   value="{{ old('waktu_mulai', date('H:i', strtotime($ujian->waktu_mulai))) }}" required>
                                            @error('waktu_mulai')
                                                <div class="text-danger small mt-1">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="waktu_selesai" class="form-label">Jam Selesai <span class="text-danger">*</span></label>
                                            <input type="time" class="form-control @error('waktu_selesai') is-invalid @enderror" 
                                                   id="waktu_selesai" name="waktu_selesai" 
                                                   value="{{ old('waktu_selesai', date('H:i', strtotime($ujian->waktu_selesai))) }}" required>
                                            @error('waktu_selesai')
                                                <div class="text-danger small mt-1">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="unit_id" class="form-label">Unit <span class="text-danger">*</span></label>
                                            <select class="form-select @error('unit_id') is-invalid @enderror" id="unit_id" name="unit_id" required>
                                                <option value="">-- Pilih Unit --</option>
                                                @foreach($units as $unit)
                                                    <option value="{{ $unit->unit_id }}" 
                                                        {{ (old('unit_id', $ujian->unit_id) == $unit->unit_id) ? 'selected' : '' }}>
                                                        {{ $unit->nama_unit }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('unit_id')
                                                <div class="text-danger small mt-1">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="pelatih_id" class="form-label">Pelatih Penguji <span class="text-danger">*</span></label>
                                            <select class="form-select @error('pelatih_id') is-invalid @enderror" id="pelatih_id" name="pelatih_id" required>
                                                <option value="">-- Pilih Pelatih --</option>
                                                @foreach($pelatih as $p)
                                                    <option value="{{ $p->pelatih_id }}" 
                                                        {{ (old('pelatih_id', $ujian->pelatih_id) == $p->pelatih_id) ? 'selected' : '' }}>
                                                        {{ $p->nama_pelatih }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('pelatih_id')
                                                <div class="text-danger small mt-1">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="status_ujian" class="form-label">Status Ujian <span class="text-danger">*</span></label>
                                            <select class="form-select @error('status_ujian') is-invalid @enderror" id="status_ujian" name="status_ujian" required>
                                                <option value="dijadwalkan" {{ (old('status_ujian', $ujian->status_ujian) == 'dijadwalkan') ? 'selected' : '' }}>Dijadwalkan</option>
                                                <option value="berlangsung" {{ (old('status_ujian', $ujian->status_ujian) == 'berlangsung') ? 'selected' : '' }}>Berlangsung</option>
                                                <option value="selesai" {{ (old('status_ujian', $ujian->status_ujian) == 'selesai') ? 'selected' : '' }}>Selesai</option>
                                                <option value="dibatalkan" {{ (old('status_ujian', $ujian->status_ujian) == 'dibatalkan') ? 'selected' : '' }}>Dibatalkan</option>
                                            </select>
                                            @error('status_ujian')
                                                <div class="text-danger small mt-1">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Tingkat Sabuk dan Biaya -->
                            <div class="mb-4">
                                <h5 class="text-warning mb-3">
                                    <i class="bi bi-award me-2"></i>
                                    Tingkat Sabuk & Biaya
                                </h5>
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="sabuk_dari" class="form-label">Sabuk Dari <span class="text-danger">*</span></label>
                                            <select class="form-select @error('sabuk_dari') is-invalid @enderror" id="sabuk_dari" name="sabuk_dari" required>
                                                <option value="">-- Pilih Tingkat Asal --</option>
                                                <option value="Sabuk Putih" {{ (old('sabuk_dari', $ujian->sabuk_dari) == 'Sabuk Putih') ? 'selected' : '' }}>Sabuk Putih</option>
                                                <option value="Sabuk Kuning" {{ (old('sabuk_dari', $ujian->sabuk_dari) == 'Sabuk Kuning') ? 'selected' : '' }}>Sabuk Kuning</option>
                                                <option value="Sabuk Hijau" {{ (old('sabuk_dari', $ujian->sabuk_dari) == 'Sabuk Hijau') ? 'selected' : '' }}>Sabuk Hijau</option>
                                                <option value="Sabuk Biru" {{ (old('sabuk_dari', $ujian->sabuk_dari) == 'Sabuk Biru') ? 'selected' : '' }}>Sabuk Biru</option>
                                                <option value="Sabuk Coklat" {{ (old('sabuk_dari', $ujian->sabuk_dari) == 'Sabuk Coklat') ? 'selected' : '' }}>Sabuk Coklat</option>
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
                                                <option value="Sabuk Kuning" {{ (old('sabuk_ke', $ujian->sabuk_ke) == 'Sabuk Kuning') ? 'selected' : '' }}>Sabuk Kuning</option>
                                                <option value="Sabuk Hijau" {{ (old('sabuk_ke', $ujian->sabuk_ke) == 'Sabuk Hijau') ? 'selected' : '' }}>Sabuk Hijau</option>
                                                <option value="Sabuk Biru" {{ (old('sabuk_ke', $ujian->sabuk_ke) == 'Sabuk Biru') ? 'selected' : '' }}>Sabuk Biru</option>
                                                <option value="Sabuk Coklat" {{ (old('sabuk_ke', $ujian->sabuk_ke) == 'Sabuk Coklat') ? 'selected' : '' }}>Sabuk Coklat</option>
                                                <option value="Sabuk Hitam" {{ (old('sabuk_ke', $ujian->sabuk_ke) == 'Sabuk Hitam') ? 'selected' : '' }}>Sabuk Hitam</option>
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
                                                       id="biaya_ujian" name="biaya_ujian" 
                                                       value="{{ old('biaya_ujian', $ujian->biaya_ujian) }}" 
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
                                                   id="kuota_peserta" name="kuota_peserta" 
                                                   value="{{ old('kuota_peserta', $ujian->kuota_peserta) }}" 
                                                   min="1" max="100" placeholder="20" required>
                                            @error('kuota_peserta')
                                                <div class="text-danger small mt-1">{{ $message }}</div>
                                            @enderror
                                            @if($ujian->jumlah_peserta > 0)
                                                <small class="text-info">
                                                    <i class="bi bi-info-circle me-1"></i>
                                                    Saat ini ada {{ $ujian->jumlah_peserta }} peserta terdaftar
                                                </small>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="persyaratan" class="form-label">Persyaratan Ujian</label>
                                    <textarea class="form-control @error('persyaratan') is-invalid @enderror" 
                                              id="persyaratan" name="persyaratan" rows="4" 
                                              placeholder="Contoh: Minimal latihan 6 bulan, menguasai jurus dasar 1-10, dll.">{{ old('persyaratan', $ujian->persyaratan) }}</textarea>
                                    @error('persyaratan')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Keterangan Tambahan -->
                            <div class="mb-4">
                                <h5 class="text-warning mb-3">
                                    <i class="bi bi-chat-left-text me-2"></i>
                                    Keterangan Tambahan
                                </h5>
                                
                                <div class="mb-3">
                                    <label for="keterangan" class="form-label">Keterangan</label>
                                    <textarea class="form-control @error('keterangan') is-invalid @enderror" 
                                              id="keterangan" name="keterangan" rows="3" 
                                              placeholder="Keterangan tambahan mengenai ujian (opsional)">{{ old('keterangan', $ujian->keterangan) }}</textarea>
                                    @error('keterangan')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Status Info -->
                            <div class="alert alert-info">
                                <i class="bi bi-info-circle me-2"></i>
                                <strong>Status saat ini:</strong> 
                                @if($ujian->status_ujian == 'dijadwalkan')
                                    <span class="badge bg-primary">Dijadwalkan</span>
                                @elseif($ujian->status_ujian == 'berlangsung')
                                    <span class="badge bg-warning">Berlangsung</span>
                                @elseif($ujian->status_ujian == 'selesai')
                                    <span class="badge bg-success">Selesai</span>
                                @elseif($ujian->status_ujian == 'dibatalkan')
                                    <span class="badge bg-danger">Dibatalkan</span>
                                @endif
                                @if($ujian->jumlah_peserta > 0)
                                    - {{ $ujian->jumlah_peserta }} peserta terdaftar
                                @endif
                            </div>

                            <!-- Warning jika ada peserta -->
                            @if($ujian->jumlah_peserta > 0)
                            <div class="alert alert-warning">
                                <i class="bi bi-exclamation-triangle me-2"></i>
                                <strong>Perhatian:</strong> Ujian ini memiliki {{ $ujian->jumlah_peserta }} peserta yang sudah terdaftar. 
                                Perubahan tertentu mungkin mempengaruhi peserta yang sudah mendaftar.
                            </div>
                            @endif

                            <!-- Tombol aksi -->
                            <div class="d-flex justify-content-between">
                                <a href="{{ route('admin.tampilUjian') }}" class="btn btn-secondary">
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
                                        Update Ujian
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
        // Validasi kuota tidak boleh kurang dari peserta yang sudah terdaftar
        const currentPeserta = {{ $ujian->jumlah_peserta }};
        document.getElementById('kuota_peserta').addEventListener('change', function() {
            const newKuota = parseInt(this.value);
            if (newKuota < currentPeserta) {
                alert(`Kuota tidak boleh kurang dari ${currentPeserta} (jumlah peserta yang sudah terdaftar)`);
                this.value = currentPeserta;
            }
        });

        // Validasi waktu
        document.getElementById('waktu_mulai').addEventListener('change', function() {
            const waktuSelesaiField = document.getElementById('waktu_selesai');
            if (this.value >= waktuSelesaiField.value && waktuSelesaiField.value) {
                alert('Waktu mulai harus lebih kecil dari waktu selesai!');
                this.focus();
            }
        });

        document.getElementById('waktu_selesai').addEventListener('change', function() {
            const waktuMulaiField = document.getElementById('waktu_mulai');
            if (waktuMulaiField.value >= this.value && waktuMulaiField.value) {
                alert('Waktu selesai harus lebih besar dari waktu mulai!');
                this.focus();
            }
        });

        // Format input biaya
        document.getElementById('biaya_ujian').addEventListener('input', function() {
            let value = this.value.replace(/[^\d]/g, '');
            this.value = value;
        });

        // Reset form to original values
        function resetForm() {
            // Reset ke nilai database
            document.getElementById('nama_ujian').value = '{{ $ujian->nama_ujian }}';
            document.getElementById('tanggal_ujian').value = '{{ $ujian->tanggal_ujian->format('Y-m-d') }}';
            document.getElementById('waktu_mulai').value = '{{ date('H:i', strtotime($ujian->waktu_mulai)) }}';
            document.getElementById('waktu_selesai').value = '{{ date('H:i', strtotime($ujian->waktu_selesai)) }}';
            document.getElementById('unit_id').value = '{{ $ujian->unit_id }}';
            document.getElementById('pelatih_id').value = '{{ $ujian->pelatih_id }}';
            document.getElementById('status_ujian').value = '{{ $ujian->status_ujian }}';
            document.getElementById('sabuk_dari').value = '{{ $ujian->sabuk_dari }}';
            document.getElementById('sabuk_ke').value = '{{ $ujian->sabuk_ke }}';
            document.getElementById('biaya_ujian').value = '{{ $ujian->biaya_ujian }}';
            document.getElementById('kuota_peserta').value = '{{ $ujian->kuota_peserta }}';
            document.getElementById('persyaratan').value = '{{ $ujian->persyaratan }}';
            document.getElementById('keterangan').value = '{{ $ujian->keterangan }}';
        }

        // Prevent form submission jika ada error validasi
        document.querySelector('form').addEventListener('submit', function(e) {
            const waktuMulai = document.getElementById('waktu_mulai').value;
            const waktuSelesai = document.getElementById('waktu_selesai').value;
            const kuotaPeserta = parseInt(document.getElementById('kuota_peserta').value);
            
            if (waktuMulai && waktuSelesai && waktuMulai >= waktuSelesai) {
                e.preventDefault();
                alert('Waktu selesai harus lebih besar dari waktu mulai!');
                return false;
            }
            
            if (kuotaPeserta < currentPeserta) {
                e.preventDefault();
                alert(`Kuota tidak boleh kurang dari ${currentPeserta} (jumlah peserta yang sudah terdaftar)`);
                return false;
            }
        });

        // Warning jika mengubah status ke dibatalkan dan ada peserta
        document.getElementById('status_ujian').addEventListener('change', function() {
            if (this.value === 'dibatalkan' && currentPeserta > 0) {
                if (!confirm(`Yakin ingin membatalkan ujian ini? Ada ${currentPeserta} peserta yang sudah terdaftar.`)) {
                    this.value = '{{ $ujian->status_ujian }}'; // Kembalikan ke status semula
                }
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