@extends('layout')

@section('konten')
    <title>Data Iuran Bulanan Dragon Eight</title>

    <div class="container-fluid py-4">
        <!-- Header Section -->
        <div class="header-section">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h1 class="mb-2">
                        <i class="bi bi-credit-card me-3"></i>
                        Data Iuran Bulanan Dragon Eight
                    </h1>
                    <p class="mb-0 opacity-75">Kelola dan pantau pembayaran iuran murid</p>
                </div>
                <div class="col-md-4 text-end">
                    <button class="btn btn-light btn-lg me-2" onclick="window.print()">
                        <i class="bi bi-printer me-2"></i>
                        Cetak Laporan
                    </button>
                    <button class="btn btn-success btn-lg" data-bs-toggle="modal" data-bs-target="#generateIuranModal">
                        <i class="bi bi-plus-circle me-2"></i>
                        Generate Iuran
                    </button>
                </div>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="stats-card">
                    <div class="stats-number text-primary">{{ $iuran->count() }}</div>
                    <div class="stats-label">Total Tagihan</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stats-card">
                    <div class="stats-number text-success">{{ $iuran->where('status', 'Lunas')->count() }}</div>
                    <div class="stats-label">Sudah Lunas</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stats-card">
                    <div class="stats-number text-danger">{{ $iuran->where('status', 'Belum Lunas')->count() }}</div>
                    <div class="stats-label">Belum Lunas</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stats-card">
                    <div class="stats-number text-warning">Rp {{ number_format($iuran->where('status', 'Lunas')->sum('nominal'), 0, ',', '.') }}</div>
                    <div class="stats-label">Total Terbayar</div>
                </div>
            </div>
        </div>

        <!-- Search and Filter Section -->
        <div class="search-section">
            <form method="GET" action="{{ route('admin.tampilIuran') }}">
                <div class="row mb-3">
                    <div class="col-md-3">
                        <label for="search" class="form-label">Cari Murid:</label>
                        <input type="text" name="search" id="search" class="form-control" 
                               value="{{ request('search') }}" placeholder="Nama murid...">
                    </div>
                    <div class="col-md-2">
                        <label for="bulan" class="form-label">Bulan:</label>
                        <select name="bulan" id="bulan" class="form-select">
                            <option value="">Semua Bulan</option>
                            @php
                                $bulanList = ['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
                            @endphp
                            @foreach($bulanList as $bulan)
                                <option value="{{ $bulan }}" {{ request('bulan') == $bulan ? 'selected' : '' }}>
                                    {{ $bulan }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label for="tahun" class="form-label">Tahun:</label>
                        <select name="tahun" id="tahun" class="form-select">
                            <option value="">Semua Tahun</option>
                            @foreach($tahunList as $tahun)
                                <option value="{{ $tahun }}" {{ request('tahun') == $tahun ? 'selected' : '' }}>
                                    {{ $tahun }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label for="unit_id" class="form-label">Unit:</label>
                        <select name="unit_id" id="unit_id" class="form-select">
                            <option value="">Semua Unit</option>
                            @foreach($units as $unit)
                                <option value="{{ $unit->unit_id }}" {{ request('unit_id') == $unit->unit_id ? 'selected' : '' }}>
                                    {{ $unit->nama_unit }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label for="status" class="form-label">Status:</label>
                        <select name="status" id="status" class="form-select">
                            <option value="">Semua Status</option>
                            <option value="Lunas" {{ request('status') == 'Lunas' ? 'selected' : '' }}>Lunas</option>
                            <option value="Belum Lunas" {{ request('status') == 'Belum Lunas' ? 'selected' : '' }}>Belum Lunas</option>
                        </select>
                    </div>
                    <div class="col-md-1">
                        <label class="form-label">&nbsp;</label>
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-search"></i>
                            </button>
                            <a href="{{ route('admin.tampilIuran') }}" class="btn btn-outline-secondary">
                                <i class="bi bi-arrow-clockwise"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <!-- Table Section -->
        <div class="table-container">
            @if($iuran->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th style="width: 20%">Nama</th>
                                <th style="width: 15%">Unit</th>
                                <th style="width: 10%">Bulan</th>
                                <th style="width: 8%">Tahun</th>
                                <th style="width: 12%">Nominal</th>
                                <th style="width: 12%">Tanggal Bayar</th>
                                <th style="width: 13%">Status Pembayaran</th>
                                <th style="width: 10%">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($iuran as $i)
                            <tr>
                                <td>
                                    <div class="murid-display">
                                        <strong>{{ $i->nama_murid }}</strong>
                                        <small class="text-muted d-block">ID: {{ $i->murid_id }}</small>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-info text-white">
                                        {{ $i->nama_unit }}
                                    </span>
                                </td>
                                <td>
                                    <span class="fw-bold">{{ $i->bulan }}</span>
                                </td>
                                <td>
                                    <span class="fw-bold">{{ $i->tahun }}</span>
                                </td>
                                <td>
                                    <span class="text-success fw-bold">
                                        Rp {{ number_format($i->nominal, 0, ',', '.') }}
                                    </span>
                                </td>
                                <td>
                                    @if($i->tanggal_bayar)
                                        <small class="text-success">
                                            {{ date('d/m/Y', strtotime($i->tanggal_bayar)) }}
                                        </small>
                                    @else
                                        <small class="text-muted">-</small>
                                    @endif
                                </td>
                                <td>
                                    @if($i->status == 'Lunas')
                                        <span class="badge bg-success">Lunas</span>
                                    @else
                                        <span class="badge bg-danger">Belum Lunas</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="action-buttons">
                                        @if($i->status == 'Belum Lunas')
                                            <button class="btn-action btn-success" 
                                                    onclick="bayarIuran({{ $i->iuran_id }})" 
                                                    title="Bayar">
                                                <i class="bi bi-check-circle"></i>
                                            </button>
                                        @endif
                                        
                                        <a href="{{ route('admin.editIuran', $i->iuran_id) }}" 
                                           class="btn-action btn-edit" title="Edit">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        
                                        <form action="{{ route('iuran.delete', $i->iuran_id) }}" method="POST" style="display: inline-block;">
                                            @csrf
                                            <button class="btn-action btn-delete" 
                                                    onclick="return confirm('Yakin ingin menghapus data iuran ini?')" 
                                                    title="Hapus">
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
                    <i class="bi bi-credit-card-2-front"></i>
                    <h4>Tidak Ada Data Iuran</h4>
                    <p>Belum ada data iuran yang tersedia atau sesuai dengan filter yang dipilih.</p>
                    <a href="{{ route('admin.tambahIuran') }}" class="btn btn-primary">
                        <i class="bi bi-plus"></i> Tambah Iuran Manual
                    </a>
                </div>
            @endif
        </div>

        <!-- Action Buttons -->
        @if($iuran->count() > 0)
        <div class="text-center mt-4">
            <a href="{{ route('admin.tambahIuran') }}" class="btn btn-primary btn-lg me-2">
                <i class="bi bi-plus-circle me-2"></i>
                Tambah Iuran Manual
            </a>
            <button class="btn btn-outline-secondary btn-lg" onclick="exportIuran()">
                <i class="bi bi-download me-2"></i>
                Export Data
            </button>
        </div>
        @endif
    </div>

    <!-- Modal Generate Iuran -->
    <div class="modal fade" id="generateIuranModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('iuran.generate') }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Generate Iuran Bulanan</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="gen_bulan" class="form-label">Bulan</label>
                            <select name="bulan" id="gen_bulan" class="form-select" required>
                                <option value="">Pilih Bulan</option>
                                @foreach($bulanList as $bulan)
                                    <option value="{{ $bulan }}">{{ $bulan }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="gen_tahun" class="form-label">Tahun</label>
                            <input type="number" name="tahun" id="gen_tahun" class="form-control" 
                                   min="2020" max="2030" value="{{ date('Y') }}" required>
                        </div>
                        <div class="alert alert-info">
                            <i class="bi bi-info-circle me-2"></i>
                            Sistem akan membuat tagihan iuran untuk semua murid yang belum memiliki tagihan pada bulan dan tahun yang dipilih.
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-success">Generate Iuran</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Bayar Iuran -->
    <div class="modal fade" id="bayarIuranModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="formBayarIuran" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Konfirmasi Pembayaran</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="tanggal_bayar" class="form-label">Tanggal Pembayaran</label>
                            <input type="date" name="tanggal_bayar" id="tanggal_bayar" 
                                   class="form-control" value="{{ date('Y-m-d') }}" required>
                        </div>
                        <div class="alert alert-success">
                            <i class="bi bi-check-circle me-2"></i>
                            Status iuran akan diubah menjadi "Lunas" setelah konfirmasi.
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-success">Konfirmasi Bayar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function bayarIuran(iuranId) {
            const form = document.getElementById('formBayarIuran');
            form.action = `/iuran/bayar/${iuranId}`;
            
            const modal = new bootstrap.Modal(document.getElementById('bayarIuranModal'));
            modal.show();
        }

        function exportIuran() {
            const params = new URLSearchParams(window.location.search);
            window.location.href = '/iuran/export?' + params.toString();
        }

        // Print functionality
        window.addEventListener('beforeprint', function() {
            document.querySelector('.search-section').style.display = 'none';
            document.querySelector('.header-section .btn').style.display = 'none';
            document.querySelectorAll('.action-buttons').forEach(el => el.style.display = 'none');
        });

        window.addEventListener('afterprint', function() {
            document.querySelector('.search-section').style.display = 'block';
            document.querySelector('.header-section .btn').style.display = 'inline-block';
            document.querySelectorAll('.action-buttons').forEach(el => el.style.display = 'block');
        });

        // Auto submit form on filter change
        document.querySelectorAll('#bulan, #tahun, #unit_id, #status').forEach(function(element) {
            element.addEventListener('change', function() {
                this.form.submit();
            });
        });

        // Show success/error messages
        @if(session('success'))
            setTimeout(function() {
                alert('{{ session('success') }}');
            }, 100);
        @endif

        @if($errors->any())
            setTimeout(function() {
                alert('{{ $errors->first() }}');
            }, 100);
        @endif
    </script>
@endsection