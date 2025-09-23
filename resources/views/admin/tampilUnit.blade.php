@extends('layout')

@section('konten')
    <title>Unit Management</title>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="container-fluid py-4">
        <!-- Header Section -->
        <div class="header-section">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h1 class="mb-2">
                        <i class="bi bi-door-closed me-3"></i>
                        Daftar Unit
                    </h1>
                    <p class="mb-0 opacity-75">Kelola dan pantau semua unit latihan karate</p>
                </div>
                <div class="col-md-4 text-end">
                    <a href="{{ route('admin.tambahUnit') }}" class="btn btn-light btn-lg">
                        <i class="bi bi-plus-circle me-2"></i>
                        Tambah Unit
                    </a>
                </div>
            </div>
        </div>

        <!-- Search and Filter Section -->
        <div class="search-section">
            <form method="GET" action="{{ route('admin.tampilUnit') }}">
                <div class="filter-section">
                    <div class="form-group">
                        <div class="search-container">
                            <input type="text" class="form-control" name="search" 
                                   placeholder="Cari nama, unit/alamat..." 
                                   value="{{ request('search') }}" id="searchInput">
                            <i class="bi bi-search search-icon"></i>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <!-- Table Section -->
        <div class="table-container">
            @if($units->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th style="width: 10%">ID Unit</th>
                                <th style="width: 30%">Nama Unit</th>
                                <th style="width: 35%">Alamat</th>
                                <th style="width: 10%">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($units as $unit)
                            <tr>
                                <td>
                                    <span class="badge bg-primary fw-bold">{{ $unit->unit_id }}</span>
                                </td>
                                <td>
                                    <div class="fw-bold">{{ $unit->nama_unit }}</div>
                                </td>
                                <td>
                                    <div class="unit-display">
                                        {{ $unit->alamat }}
                                    </div>
                                    @if($unit->kota)
                                        <small class="text-muted d-block mt-1">{{ $unit->kota }}</small>
                                    @endif
                                </td>
                                <td>
                                    <div class="action-buttons">
                                        <a href="{{ route('admin.profilKlub', $unit->unit_id) }}" 
                                           class="btn-action btn-view" title="Lihat Detail">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        
                                        <a href="{{ route('admin.editUnit', $unit->unit_id) }}" 
                                           class="btn-action btn-edit" title="Edit Unit">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        
                                        <form action="{{ route('unit.delete', ['id' => $unit->unit_id]) }}" method="POST" style="display: inline-block;">
                                            @csrf
                                            <button class="btn-action btn-delete" 
                                                    onclick="return confirm('Yakin ingin menghapus unit ini?')" 
                                                    title="Hapus Unit">
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
                    <i class="bi bi-door-closed"></i>
                    <h4>Tidak Ada Data Unit</h4>
                    <p>Belum ada unit yang terdaftar atau sesuai dengan filter yang dipilih.</p>
                                                        <a href="{{ route('admin.tambahUnit') }}" class="btn btn-primary">
                        <i class="bi bi-plus"></i> Tambah Unit Baru
                    </a>
                </div>
            @endif
        </div>

        <!-- Action Buttons -->
        @if($units->count() > 0)
        <div class="text-center mt-4">
            <a href="{{ route('admin.tambahUnit') }}" class="btn btn-primary btn-lg me-2">
                <i class="bi bi-plus-circle me-2"></i>
                Tambah Unit Baru
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
                if (rows[i].cells.length < 4) continue;
                
                const namaUnit = rows[i].cells[1].textContent.toLowerCase();
                const alamat = rows[i].cells[2].textContent.toLowerCase();
                
                if (namaUnit.includes(searchTerm) || alamat.includes(searchTerm)) {
                    rows[i].style.display = '';
                } else {
                    rows[i].style.display = 'none';
                }
            }
        }

        function exportUnit() {
            // Implementation for export functionality
            window.location.href = '/unit/export';
        }
    </script>
@endsection