@extends('layout')

@section('konten')
    <title>Jadwal Latihan Keseluruhan</title>

    <div class="container-fluid py-4">
        <!-- Header Section -->
        <div class="header-section">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h1 class="mb-2">
                        <i class="bi bi-calendar3 me-3"></i>
                        Jadwal Latihan Keseluruhan
                    </h1>
                    <p class="mb-0 opacity-75">Kelola dan pantau semua jadwal latihan di seluruh unit</p>
                </div>
                <div class="col-md-4 text-end">
                    <button class="btn btn-light btn-lg" onclick="window.print()">
                        <i class="bi bi-printer me-2"></i>
                        Cetak Jadwal
                    </button>
                </div>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="stats-card">
                    <div class="stats-number text-primary">{{ $jadwal->count() }}</div>
                    <div class="stats-label">Total Jadwal</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stats-card">
                    <div class="stats-number text-success">{{ $jadwal->where('status', 'aktif')->count() }}</div>
                    <div class="stats-label">Jadwal Aktif</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stats-card">
                    <div class="stats-number text-info">{{ $jadwal->unique('unit_id')->count() }}</div>
                    <div class="stats-label">Unit Terlibat</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stats-card">
                    <div class="stats-number text-warning">{{ $jadwal->unique('pelatih_id')->count() }}</div>
                    <div class="stats-label">Pelatih Aktif</div>
                </div>
            </div>
        </div>

        <!-- Search and Filter Section -->
        <div class="search-section">
            <form method="GET" action="{{ route('admin.tampilJadwal') }}">
                <div class="filter-section">
                    <div class="form-group">
                        <label for="hari" class="form-label">Filter Hari:</label>
                        <select name="hari" id="hari" class="form-select" onchange="this.form.submit()">
                            <option value="">Semua Hari</option>
                            <option value="Senin" {{ request('hari') == 'Senin' ? 'selected' : '' }}>Senin</option>
                            <option value="Selasa" {{ request('hari') == 'Selasa' ? 'selected' : '' }}>Selasa</option>
                            <option value="Rabu" {{ request('hari') == 'Rabu' ? 'selected' : '' }}>Rabu</option>
                            <option value="Kamis" {{ request('hari') == 'Kamis' ? 'selected' : '' }}>Kamis</option>
                            <option value="Jumat" {{ request('hari') == 'Jumat' ? 'selected' : '' }}>Jumat</option>
                            <option value="Sabtu" {{ request('hari') == 'Sabtu' ? 'selected' : '' }}>Sabtu</option>
                            <option value="Minggu" {{ request('hari') == 'Minggu' ? 'selected' : '' }}>Minggu</option>
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
                        <label for="status" class="form-label">Status:</label>
                        <select name="status" id="status" class="form-select" onchange="this.form.submit()">
                            <option value="">Semua Status</option>
                            <option value="aktif" {{ request('status') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                            <option value="tidak_aktif" {{ request('status') == 'tidak_aktif' ? 'selected' : '' }}>Tidak Aktif</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <a href="{{ route('admin.tampilJadwal') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-clockwise"></i> Reset Filter
                        </a>
                    </div>
                </div>
            </form>
        </div>

        <!-- Table Section -->
        <div class="table-container">
            @if($jadwal->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th style="width: 10%">Hari</th>
                                <th style="width: 15%">Jam</th>
                                <th style="width: 20%">Unit</th>
                                <th style="width: 15%">Pelatih</th>
                                <th style="width: 10%">Murid</th>
                                <th style="width: 20%">Keterangan</th>
                                <th style="width: 10%">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($jadwal as $j)
                            <tr>
                                <td>
                                    <span class="badge badge-hari 
                                        @switch($j->hari)
                                            @case('Senin') bg-primary @break
                                            @case('Selasa') bg-success @break
                                            @case('Rabu') bg-info @break
                                            @case('Kamis') bg-warning text-dark @break
                                            @case('Jumat') bg-danger @break
                                            @case('Sabtu') bg-secondary @break
                                            @case('Minggu') bg-dark @break
                                            @default bg-light text-dark @break
                                        @endswitch
                                    ">
                                        {{ $j->hari }}
                                    </span>
                                </td>
                                <td>
                                    <div class="time-display">
                                        {{ date('H:i', strtotime($j->jam_mulai)) }}<br>
                                        <small>{{ date('H:i', strtotime($j->jam_selesai)) }}</small>
                                    </div>
                                </td>
                                <td>
                                    <div class="unit-display">
                                        {{ $j->nama_unit }}
                                    </div>
                                    <small class="text-muted d-block mt-1">
                                        {{ Str::limit($j->alamat_unit, 30) }}
                                    </small>
                                </td>
                                <td>
                                    <div class="pelatih-display">
                                        {{ $j->nama_pelatih }}
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-info">
                                        {{ $j->jumlah_murid }}
                                    </span>
                                    <small class="text-muted d-block">murid</small>
                                </td>
                                <td>
                                    <small class="text-muted">
                                        {{ $j->keterangan ?? 'Tidak ada keterangan' }}
                                    </small>
                                    @if($j->status == 'tidak_aktif')
                                        <br><span class="badge bg-secondary">Non-Aktif</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="action-buttons">
                                        <button class="btn-action btn-view" title="Lihat Detail" 
                                                onclick="showJadwalDetail({{ $j->jadwal_id }})">
                                            <i class="bi bi-eye"></i>
                                        </button>
                                        
                                        <a href="{{ route('admin.editJadwal', $j->jadwal_id) }}" 
                                           class="btn-action btn-edit" title="Edit Jadwal">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        
                                        <form action="{{ route('jadwal.delete', $j->jadwal_id) }}" method="POST" style="display: inline-block;">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn-action btn-delete" 
                                                    onclick="return confirm('Yakin ingin menghapus jadwal ini?')" 
                                                    title="Hapus Jadwal">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="empty-state">
                    <i class="bi bi-calendar-x"></i>
                    <h4>Tidak Ada Jadwal</h4>
                    <p>Belum ada jadwal latihan yang tersedia atau sesuai dengan filter yang dipilih.</p>
                    <a href="{{ route('admin.tambahJadwal') }}" class="btn btn-primary">
                        <i class="bi bi-plus"></i> Tambah Jadwal Baru
                    </a>
                </div>
            @endif
        </div>

        <!-- Action Buttons -->
        @if($jadwal->count() > 0)
        <div class="text-center mt-4">
            <a href="{{ route('admin.tambahJadwal') }}" class="btn btn-primary btn-lg me-2">
                <i class="bi bi-plus-circle me-2"></i>
                Tambah Jadwal Baru
            </a>
            <button class="btn btn-outline-secondary btn-lg" onclick="exportJadwal()">
                <i class="bi bi-download me-2"></i>
                Export Data
            </button>
        </div>
        @endif
    </div>

    <!-- Modal Detail Jadwal -->
    <div class="modal fade" id="detailJadwalModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Detail Jadwal</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" id="modalDetailContent">
                    <!-- Content will be loaded here -->
                </div>
            </div>
        </div>
    </div>

    <script>
        function showJadwalDetail(jadwalId) {
            // Implementasi untuk menampilkan detail jadwal
            const modal = new bootstrap.Modal(document.getElementById('detailJadwalModal'));
            
            // Ajax call untuk mendapatkan detail jadwal
            fetch(`/jadwal/detail/${jadwalId}`)
                .then(response => response.json())
                .then(data => {
                    document.getElementById('modalDetailContent').innerHTML = `
                        <div class="row">
                            <div class="col-md-6">
                                <h6>Informasi Jadwal</h6>
                                <p><strong>Hari:</strong> ${data.hari}</p>
                                <p><strong>Waktu:</strong> ${data.jam_mulai} - ${data.jam_selesai}</p>
                                <p><strong>Unit:</strong> ${data.nama_unit}</p>
                                <p><strong>Pelatih:</strong> ${data.nama_pelatih}</p>
                            </div>
                            <div class="col-md-6">
                                <h6>Statistik</h6>
                                <p><strong>Jumlah Murid:</strong> ${data.jumlah_murid}</p>
                                <p><strong>Status:</strong> ${data.status}</p>
                                <p><strong>Keterangan:</strong> ${data.keterangan || 'Tidak ada'}</p>
                            </div>
                        </div>
                    `;
                    modal.show();
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Gagal memuat detail jadwal');
                });
        }

        function exportJadwal() {
            // Implementasi export data jadwal
            window.location.href = '/jadwal/export';
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
    </script>
@endsection