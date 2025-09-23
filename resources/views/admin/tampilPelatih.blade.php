@extends('layout')

@section('konten')
    <title>Daftar Pelatih</title>

    <div class="container-fluid py-4">
        <!-- Header Section -->
        <div class="header-section">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h1 class="mb-2">
                        <i class="bi bi-person-badge me-3"></i>
                        Daftar Pelatih
                    </h1>
                    <p class="mb-0 opacity-75">Kelola dan pantau data semua pelatih di seluruh unit</p>
                </div>
                <div class="col-md-4 text-end">
                    <a href="{{ route('admin.tambahPelatih') }}" class="btn btn-light btn-lg">
                        <i class="bi bi-plus-circle me-2"></i>
                        Tambah Pelatih
                    </a>
                </div>
            </div>
        </div>

        <!-- Search and Filter Section -->
        <div class="search-section">
            <form method="GET" action="{{ route('admin.tampilPelatih') }}">
                <div class="filter-section">
                    <div class="form-group">
                        <div class="search-container">
                            <input type="text" class="form-control" name="search" 
                                   placeholder="Cari Pelatih..." 
                                   value="{{ request('search') }}" id="searchInput">
                            <i class="bi bi-search search-icon"></i>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <!-- Table Section -->
        <div class="table-container">
            @if($pelatih->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th style="width: 25%">Nama Pelatih</th>
                                <th style="width: 30%">Alamat</th>
                                <th style="width: 15%">No. Hp</th>
                                <th style="width: 10%">Status</th>
                                <th style="width: 10%">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($pelatih as $data)
                            <tr>
                                <td>
                                    <div class="fw-bold">{{ $data->nama_pelatih }}</div>
                                </td>
                                <td>
                                    <div class="pelatih-display">
                                        {{ Str::limit($data->alamat, 40) }}
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-info">{{ $data->no_hp }}</span>
                                </td>
                                <td>
                                    <span class="badge {{ ($data->status ?? 'aktif') == 'aktif' ? 'bg-success' : 'bg-secondary' }}">
                                        {{ ucfirst($data->status ?? 'aktif') }}
                                    </span>
                                </td>
                                <td>
                                    <div class="action-buttons">
                                        <a href="{{ route('admin.profilPelatih', $data->pelatih_id) }}" 
                                           class="btn-action btn-view" title="Lihat Detail">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        
                                        <a href="{{ route('admin.editPelatih', $data->pelatih_id) }}" 
                                           class="btn-action btn-edit" title="Edit Pelatih">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        
                                        <form action="{{ route('pelatih.delete', ['id' => $data->pelatih_id]) }}" method="POST" style="display: inline-block;">
                                            @csrf
                                            <button class="btn-action btn-delete" 
                                                    onclick="return confirm('Yakin ingin menghapus pelatih ini?')" 
                                                    title="Hapus Pelatih">
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
                    <i class="bi bi-person-badge"></i>
                    <h4>Tidak Ada Data Pelatih</h4>
                    <p>Belum ada pelatih yang terdaftar atau sesuai dengan filter yang dipilih.</p>
                    <a href="{{ route('admin.tambahPelatih') }}" class="btn btn-primary">
                        <i class="bi bi-plus"></i> Tambah Pelatih Baru
                    </a>
                </div>
            @endif
        </div>

        <!-- Action Buttons -->
        @if($pelatih->count() > 0)
        <div class="text-center mt-4">
            <a href="{{ route('admin.tambahPelatih') }}" class="btn btn-primary btn-lg me-2">
                <i class="bi bi-plus-circle me-2"></i>
                Tambah Pelatih Baru
            </a>
        </div>
        @endif
    </div>

    <script>
        // Search functionality
        document.getElementById('searchInput').addEventListener('input', function(e) {
            const searchTerm = e.target.value.toLowerCase();
            filterTable(searchTerm);
        });
        
        function filterTable(searchTerm) {
            const tbody = document.querySelector('tbody');
            const rows = tbody.getElementsByTagName('tr');
            
            for (let i = 0; i < rows.length; i++) {
                if (rows[i].cells.length < 5) continue;
                
                const namaPelatih = rows[i].cells[0].textContent.toLowerCase();
                const alamat = rows[i].cells[1].textContent.toLowerCase();
                const noHp = rows[i].cells[2].textContent.toLowerCase();
                
                if (namaPelatih.includes(searchTerm) || 
                    alamat.includes(searchTerm) || 
                    noHp.includes(searchTerm)) {
                    rows[i].style.display = '';
                } else {
                    rows[i].style.display = 'none';
                }
            }
        }

        function exportPelatih() {
            // Implementation for export functionality
            window.location.href = '/pelatih/export';
        }
    </script>

    <style>
        .bg-pink {
            background-color: #e91e63 !important;
            color: white;
        }
    </style>
@endsection