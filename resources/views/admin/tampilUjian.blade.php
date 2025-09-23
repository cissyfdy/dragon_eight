@extends('layout')

@section('konten')
    <title>Kelola Ujian Kenaikan Tingkat</title>

    <div class="container-fluid py-4">
        <!-- Header Section -->
        <div class="header-section">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h1 class="mb-2">
                        <i class="bi bi-award me-3"></i>
                        Kelola Ujian Kenaikan Tingkat
                    </h1>
                    <p class="mb-0 opacity-75">Kelola dan pantau semua ujian kenaikan tingkat sabuk</p>
                </div>
                <div class="col-md-4 text-end">
                    <button class="btn btn-light btn-lg" onclick="window.print()">
                        <i class="bi bi-printer me-2"></i>
                        Cetak Data
                    </button>
                </div>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="stats-card">
                    <div class="stats-number text-primary">{{ $statistics['total_ujian'] }}</div>
                    <div class="stats-label">Total Ujian</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stats-card">
                    <div class="stats-number text-success">{{ $statistics['ujian_aktif'] }}</div>
                    <div class="stats-label">Ujian Dijadwalkan</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stats-card">
                    <div class="stats-number text-info">{{ $statistics['ujian_selesai'] }}</div>
                    <div class="stats-label">Ujian Selesai</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stats-card">
                    <div class="stats-number text-warning">{{ $statistics['total_peserta'] }}</div>
                    <div class="stats-label">Total Peserta</div>
                </div>
            </div>
        </div>

        <!-- Search and Filter Section -->
        <div class="search-section">
            <form method="GET" action="{{ route('admin.tampilUjian') }}">
                <div class="filter-section">
                    <div class="form-group">
                        <label for="status" class="form-label">Status Ujian:</label>
                        <select name="status" id="status" class="form-select" onchange="this.form.submit()">
                            <option value="">Semua Status</option>
                            <option value="dijadwalkan" {{ request('status') == 'dijadwalkan' ? 'selected' : '' }}>Dijadwalkan</option>
                            <option value="berlangsung" {{ request('status') == 'berlangsung' ? 'selected' : '' }}>Berlangsung</option>
                            <option value="selesai" {{ request('status') == 'selesai' ? 'selected' : '' }}>Selesai</option>
                            <option value="dibatalkan" {{ request('status') == 'dibatalkan' ? 'selected' : '' }}>Dibatalkan</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="unit_id" class="form-label">Filter Unit:</label>
                        <select name="unit_id" id="unit_id" class="form-select" onchange="this.form.submit()">
                            <option value="">Semua Unit</option>
                            @foreach($units as $unit)
                                <option value="{{ $unit->unit_id }}" {{ request('unit_id') == $unit->unit_id ? 'selected' : '' }}>
                                    {{ $unit->nama_unit }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="sabuk_dari" class="form-label">Tingkat Sabuk:</label>
                        <select name="sabuk_dari" id="sabuk_dari" class="form-select" onchange="this.form.submit()">
                            <option value="">Semua Tingkat</option>
                            <option value="Sabuk Putih" {{ request('sabuk_dari') == 'Sabuk Putih' ? 'selected' : '' }}>Sabuk Putih</option>
                            <option value="Sabuk Kuning" {{ request('sabuk_dari') == 'Sabuk Kuning' ? 'selected' : '' }}>Sabuk Kuning</option>
                            <option value="Sabuk Hijau" {{ request('sabuk_dari') == 'Sabuk Hijau' ? 'selected' : '' }}>Sabuk Hijau</option>
                            <option value="Sabuk Biru" {{ request('sabuk_dari') == 'Sabuk Biru' ? 'selected' : '' }}>Sabuk Biru</option>
                            <option value="Sabuk Coklat" {{ request('sabuk_dari') == 'Sabuk Coklat' ? 'selected' : '' }}>Sabuk Coklat</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="tanggal_dari" class="form-label">Periode:</label>
                        <div class="input-group">
                            <input type="date" name="tanggal_dari" id="tanggal_dari" class="form-control" 
                                   value="{{ request('tanggal_dari') }}" onchange="this.form.submit()">
                            <span class="input-group-text">s/d</span>
                            <input type="date" name="tanggal_sampai" id="tanggal_sampai" class="form-control" 
                                   value="{{ request('tanggal_sampai') }}" onchange="this.form.submit()">
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <a href="{{ route('admin.tampilUjian') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-clockwise"></i> Reset Filter
                        </a>
                    </div>
                </div>
            </form>
        </div>

        <!-- Table Section -->
        <div class="table-container">
            @if($ujian->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th style="width: 20%">Nama Ujian</th>
                                <th style="width: 12%">Tanggal</th>
                                <th style="width: 15%">Unit</th>
                                <th style="width: 12%">Pelatih</th>
                                <th style="width: 15%">Tingkat Sabuk</th>
                                <th style="width: 10%">Peserta</th>
                                <th style="width: 8%">Biaya</th>
                                <th style="width: 8%">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($ujian as $u)
                            <tr>
                                <td>
                                    <div class="ujian-display">
                                        <strong>{{ $u->nama_ujian }}</strong>
                                        <div class="mt-1">
                                            <span class="badge 
                                                @switch($u->status_ujian)
                                                    @case('dijadwalkan') bg-primary @break
                                                    @case('berlangsung') bg-warning text-dark @break
                                                    @case('selesai') bg-success @break
                                                    @case('dibatalkan') bg-danger @break
                                                    @default bg-secondary @break
                                                @endswitch
                                            ">
                                                {{ ucfirst($u->status_ujian) }}
                                            </span>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="date-display">
                                        <strong>{{ $u->tanggal_ujian->format('d/m/Y') }}</strong><br>
                                        <small class="text-muted">
                                            {{ date('H:i', strtotime($u->waktu_mulai)) }} - 
                                            {{ date('H:i', strtotime($u->waktu_selesai)) }}
                                        </small>
                                    </div>
                                </td>
                                <td>
                                    <div class="unit-display">
                                        {{ $u->unit->nama_unit }}
                                    </div>
                                    <small class="text-muted d-block mt-1">
                                        {{ Str::limit($u->unit->alamat, 25) }}
                                    </small>
                                </td>
                                <td>
                                    <div class="pelatih-display">
                                        {{ $u->pelatih->nama_pelatih }}
                                    </div>
                                </td>
                                <td>
                                    <div class="sabuk-display">
                                        <span class="badge bg-light text-dark">{{ $u->sabuk_dari }}</span>
                                        <i class="bi bi-arrow-right mx-1"></i>
                                        <span class="badge bg-dark">{{ $u->sabuk_ke }}</span>
                                    </div>
                                </td>
                                <td>
                                    <div class="peserta-info">
                                        <span class="badge 
                                            @if($u->status_kuota == 'penuh') bg-danger
                                            @elseif($u->status_kuota == 'terbatas') bg-warning text-dark
                                            @else bg-success
                                            @endif
                                        ">
                                            {{ $u->jumlah_peserta }}/{{ $u->kuota_peserta }}
                                        </span>
                                        <small class="text-muted d-block">
                                            @if($u->status_kuota == 'penuh') 
                                                Penuh
                                            @elseif($u->status_kuota == 'terbatas') 
                                                Terbatas
                                            @else 
                                                Tersedia
                                            @endif
                                        </small>
                                    </div>
                                </td>
                                <td>
                                    <div class="biaya-display">
                                        <small>Rp</small>
                                        <strong>{{ number_format($u->biaya_ujian, 0, ',', '.') }}</strong>
                                    </div>
                                </td>
                                <td>
                                    <div class="action-buttons">
                                        <button class="btn-action btn-view" title="Lihat Detail" 
                                                onclick="showUjianDetail({{ $u->ujian_id }})">
                                            <i class="bi bi-eye"></i>
                                        </button>
                                        
                                        <a href="{{ route('admin.editUjian', $u->ujian_id) }}" 
                                           class="btn-action btn-edit" title="Edit Ujian">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        
                                        @if($u->jumlah_peserta == 0)
                                        <form action="{{ route('deleteUjian', $u->ujian_id) }}" method="POST" style="display: inline-block;">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn-action btn-delete" 
                                                    onclick="return confirm('Yakin ingin menghapus ujian ini?')" 
                                                    title="Hapus Ujian">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                        @else
                                        <button class="btn-action btn-delete" disabled title="Tidak dapat dihapus - Ada peserta terdaftar">
                                            <i class="bi bi-lock"></i>
                                        </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="empty-state">
                    <i class="bi bi-award-fill"></i>
                    <h4>Tidak Ada Ujian</h4>
                    <p>Belum ada ujian yang tersedia atau sesuai dengan filter yang dipilih.</p>
                    <a href="{{ route('admin.tambahUjian') }}" class="btn btn-primary">
                        <i class="bi bi-plus"></i> Tambah Ujian Baru
                    </a>
                </div>
            @endif
        </div>

        <!-- Action Buttons -->
        @if($ujian->count() > 0)
        <div class="text-center mt-4">
            <a href="{{ route('admin.tambahUjian') }}" class="btn btn-primary btn-lg me-2">
                <i class="bi bi-plus-circle me-2"></i>
                Tambah Ujian Baru
            </a>
            <button class="btn btn-outline-secondary btn-lg" onclick="exportUjian()">
                <i class="bi bi-download me-2"></i>
                Export Data
            </button>
        </div>
        @endif
    </div>

    <!-- Modal Detail Ujian -->
    <div class="modal fade" id="detailUjianModal" tabindex="-1">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Detail Ujian</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" id="modalDetailContent">
                    <!-- Content will be loaded here -->
                </div>
            </div>
        </div>
    </div>

    <script>
        function showUjianDetail(ujianId) {
            const modal = new bootstrap.Modal(document.getElementById('detailUjianModal'));
            
            // Ajax call untuk mendapatkan detail ujian
            fetch(`/admin/ujian/detail/${ujianId}`)
                .then(response => response.json())
                .then(data => {
                    document.getElementById('modalDetailContent').innerHTML = `
                        <div class="row">
                            <div class="col-md-6">
                                <h6 class="mb-3"><i class="bi bi-info-circle me-2"></i>Informasi Ujian</h6>
                                <div class="detail-item">
                                    <strong>Nama Ujian:</strong>
                                    <span>${data.nama_ujian}</span>
                                </div>
                                <div class="detail-item">
                                    <strong>Tanggal & Waktu:</strong>
                                    <span>${data.tanggal_ujian}, ${data.waktu_mulai} - ${data.waktu_selesai}</span>
                                </div>
                                <div class="detail-item">
                                    <strong>Unit:</strong>
                                    <span>${data.nama_unit}</span>
                                    <small class="d-block text-muted">${data.alamat_unit}</small>
                                </div>
                                <div class="detail-item">
                                    <strong>Pelatih:</strong>
                                    <span>${data.nama_pelatih}</span>
                                </div>
                                <div class="detail-item">
                                    <strong>Tingkat Sabuk:</strong>
                                    <span class="badge bg-light text-dark">${data.sabuk_dari}</span>
                                    <i class="bi bi-arrow-right mx-1"></i>
                                    <span class="badge bg-dark">${data.sabuk_ke}</span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <h6 class="mb-3"><i class="bi bi-graph-up me-2"></i>Statistik</h6>
                                <div class="detail-item">
                                    <strong>Biaya Ujian:</strong>
                                    <span class="text-success">Rp ${data.biaya_ujian}</span>
                                </div>
                                <div class="detail-item">
                                    <strong>Kuota Peserta:</strong>
                                    <span>${data.kuota_peserta} orang</span>
                                </div>
                                <div class="detail-item">
                                    <strong>Peserta Terdaftar:</strong>
                                    <span class="badge ${data.sisa_kuota <= 0 ? 'bg-danger' : data.sisa_kuota <= 5 ? 'bg-warning text-dark' : 'bg-success'}">
                                        ${data.jumlah_peserta}/${data.kuota_peserta}
                                    </span>
                                </div>
                                <div class="detail-item">
                                    <strong>Sisa Kuota:</strong>
                                    <span>${data.sisa_kuota} tempat</span>
                                </div>
                                <div class="detail-item">
                                    <strong>Status:</strong>
                                    <span class="badge ${data.status_ujian === 'dijadwalkan' ? 'bg-primary' : 
                                        data.status_ujian === 'berlangsung' ? 'bg-warning text-dark' : 
                                        data.status_ujian === 'selesai' ? 'bg-success' : 'bg-danger'}">
                                        ${data.status_ujian.charAt(0).toUpperCase() + data.status_ujian.slice(1)}
                                    </span>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-12">
                                <h6 class="mb-3"><i class="bi bi-list-check me-2"></i>Persyaratan</h6>
                                <div class="detail-content">
                                    ${data.persyaratan || 'Tidak ada persyaratan khusus'}
                                </div>
                            </div>
                        </div>
                        ${data.keterangan ? `
                        <hr>
                        <div class="row">
                            <div class="col-12">
                                <h6 class="mb-3"><i class="bi bi-chat-left-text me-2"></i>Keterangan</h6>
                                <div class="detail-content">
                                    ${data.keterangan}
                                </div>
                            </div>
                        </div>` : ''}
                        ${data.peserta.length > 0 ? `
                        <hr>
                        <div class="row">
                            <div class="col-12">
                                <h6 class="mb-3"><i class="bi bi-people me-2"></i>Daftar Peserta (${data.peserta.length})</h6>
                                <div class="table-responsive">
                                    <table class="table table-sm">
                                        <thead>
                                            <tr>
                                                <th>Nama Murid</th>
                                                <th>Status Pendaftaran</th>
                                                <th>Status Pembayaran</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            ${data.peserta.map(peserta => `
                                                <tr>
                                                    <td>${peserta.nama_murid}</td>
                                                    <td>
                                                        <span class="badge ${peserta.status_pendaftaran === 'diterima' ? 'bg-success' : 
                                                            peserta.status_pendaftaran === 'terdaftar' ? 'bg-primary' : 'bg-danger'}">
                                                            ${peserta.status_pendaftaran.charAt(0).toUpperCase() + peserta.status_pendaftaran.slice(1)}
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <span class="badge ${peserta.status_pembayaran === 'sudah_bayar' ? 'bg-success' : 'bg-warning text-dark'}">
                                                            ${peserta.status_pembayaran === 'sudah_bayar' ? 'Sudah Bayar' : 'Belum Bayar'}
                                                        </span>
                                                    </td>
                                                </tr>
                                            `).join('')}
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>` : ''}
                        
                        <style>
                            .detail-item {
                                margin-bottom: 15px;
                                padding-bottom: 10px;
                                border-bottom: 1px solid #f0f0f0;
                            }
                            .detail-item strong {
                                display: inline-block;
                                width: 140px;
                                color: #333;
                            }
                            .detail-content {
                                background-color: #f8f9fa;
                                padding: 15px;
                                border-radius: 8px;
                                border-left: 4px solid #007bff;
                            }
                        </style>
                    `;
                    modal.show();
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Gagal memuat detail ujian');
                });
        }

        function exportUjian() {
            window.location.href = '/admin/ujian/export';
        }

        // Print functionality
        window.addEventListener('beforeprint', function() {
            document.querySelector('.search-section').style.display = 'none';
            document.querySelector('.header-section .btn').style.display = 'none';
        });

        window.addEventListener('afterprint', function() {
            document.querySelector('.search-section').style.display = 'block';
            document.querySelector('.header-section .btn').style.display = 'block';
        });

        // Show success/error messages
        @if(session('success'))
            const successToast = `
                <div class="toast align-items-center text-bg-success border-0" role="alert" aria-live="assertive" aria-atomic="true">
                    <div class="d-flex">
                        <div class="toast-body">
                            <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                        </div>
                        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
                    </div>
                </div>
            `;
            showToast(successToast);
        @endif

        @if(session('error'))
            const errorToast = `
                <div class="toast align-items-center text-bg-danger border-0" role="alert" aria-live="assertive" aria-atomic="true">
                    <div class="d-flex">
                        <div class="toast-body">
                            <i class="bi bi-exclamation-triangle me-2"></i>{{ session('error') }}
                        </div>
                        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
                    </div>
                </div>
            `;
            showToast(errorToast);
        @endif

        function showToast(toastHtml) {
            const toastContainer = document.createElement('div');
            toastContainer.className = 'toast-container position-fixed top-0 end-0 p-3';
            toastContainer.innerHTML = toastHtml;
            document.body.appendChild(toastContainer);
            
            const toast = new bootstrap.Toast(toastContainer.querySelector('.toast'));
            toast.show();
            
            setTimeout(() => {
                document.body.removeChild(toastContainer);
            }, 5000);
        }
    </script>

    
@endsection