@extends('layout')

@section('konten')
    <title>Daftar Murid</title>

    <div class="container-fluid py-4">
        <!-- Header Section -->
        <div class="header-section">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h1 class="mb-2">
                        <i class="bi bi-people me-3"></i>
                        Daftar Murid Keseluruhan
                    </h1>
                    <p class="mb-0 opacity-75">Kelola dan pantau data semua murid di seluruh unit</p>
                </div>
                <div class="col-md-4 text-end">
                    <a href="{{ route('admin.tambahMurid') }}" class="btn btn-light btn-lg">
                        <i class="bi bi-plus-circle me-2"></i>
                        Tambah Murid
                    </a>
                </div>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="stats-card">
                    <div class="stats-number text-primary">{{ $murid->count() }}</div>
                    <div class="stats-label">Total Murid</div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="stats-card">
                    <div class="stats-number text-info">{{ $murid->unique('unit_id')->count() }}</div>
                    <div class="stats-label">Unit Terlibat</div>
                </div>
            </div>

        </div>

        <!-- Search and Filter Section -->
        <div class="search-section">
            <form method="GET" action="{{ route('admin.tampilMurid') }}">
                <div class="filter-section">
                    <div class="form-group">
                        <div class="search-container">
                            <input type="text" class="form-control" name="search" 
                                   placeholder="Cari nama/no.reg..." 
                                   value="{{ request('search') }}" id="searchInput">
                            <i class="bi bi-search search-icon"></i>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="unit_id" class="form-label">Filter Unit:</label>
                        <select name="unit_id" id="unit_id" class="form-select" onchange="this.form.submit()">
                            <option value="">Semua Unit</option>
                            @if(isset($units))
                                @foreach($units as $unit)
                                    <option value="{{ $unit->unit_id }}" {{ request('unit_id') == $unit->unit_id ? 'selected' : '' }}>
                                        {{ $unit->nama_unit }}
                                    </option>
                                @endforeach
                            @else
                                {{-- Fallback jika $units tidak tersedia --}}
                                @php
                                    $uniqueUnits = $murid->unique('unit_id')->filter(function($item) {
                                        return !is_null($item->unit_id) && !is_null($item->unit);
                                    });
                                @endphp
                                @foreach($uniqueUnits as $unitData)
                                    <option value="{{ $unitData->unit_id }}" {{ request('unit_id') == $unitData->unit_id ? 'selected' : '' }}>
                                        {{ $unitData->unit->nama_unit }}
                                    </option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="tingkat_sabuk" class="form-label">Tingkat Sabuk:</label>
                        <select name="tingkat_sabuk" id="tingkat_sabuk" class="form-select" onchange="this.form.submit()">
                            <option value="">Semua Tingkat</option>
                            @php
                                $tingkatSabukList = $murid->whereNotNull('tingkat_sabuk')
                                                         ->where('tingkat_sabuk', '!=', '')
                                                         ->pluck('tingkat_sabuk')
                                                         ->unique()
                                                         ->sort();
                            @endphp
                            @foreach($tingkatSabukList as $sabuk)
                                <option value="{{ $sabuk }}" {{ request('tingkat_sabuk') == $sabuk ? 'selected' : '' }}>
                                    {{ $sabuk }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <a href="{{ route('admin.tampilMurid') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-clockwise"></i> Reset Filter
                        </a>
                    </div>
                </div>
            </form>
        </div>

        <!-- Table Section -->
        <div class="table-container">
            @if($murid->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th style="width: 15%">No Register</th>
                                <th style="width: 25%">Nama Murid</th>
                                <th style="width: 20%">Unit</th>
                                <th style="width: 15%">Tingkat Sabuk</th>
                                <th style="width: 15%">Tanggal Lahir</th>
                                <th style="width: 10%">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($murid as $data)
                            <tr>
                                <td>
                                    <span class="badge bg-primary">{{ $data->nomor_register }}</span>
                                </td>
                                <td>
                                    <div class="fw-bold">{{ $data->nama_murid }}</div>
                                </td>
                                <td>
                                    <div class="unit-display">
                                        {{ $data->unit->nama_unit ?? 'Unit tidak ditemukan' }}
                                    </div>
                                </td>
                                <td>
                                    <span class="badge 
                                        @switch($data->tingkat_sabuk)
                                            @case('Putih') bg-light text-dark @break
                                            @case('Kuning') bg-warning text-dark @break
                                            @case('Hijau') bg-success @break
                                            @case('Biru') bg-info @break
                                            @case('Coklat') bg-secondary @break
                                            @case('Hitam') bg-dark @break
                                            @default bg-light text-dark @break
                                        @endswitch
                                    ">
                                        {{ $data->tingkat_sabuk ?? '-' }}
                                    </span>
                                </td>
                                <td>
                                    <div class="time-display">
                                        {{ $data->tanggal_lahir ? date('d/m/Y', strtotime($data->tanggal_lahir)) : '-' }}
                                    </div>
                                </td>
                                <td>
                                    <div class="action-buttons">
                                        <a href="{{ route('admin.profilMurid', $data->murid_id) }}" 
                                           class="btn-action btn-view" title="Lihat Detail">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        
                                        <a href="{{ route('admin.editMurid', $data->murid_id) }}" 
                                           class="btn-action btn-edit" title="Edit Murid">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        
                                        <form action="{{ route('murid.delete', ['id' => $data->murid_id]) }}" method="POST" style="display: inline-block;">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn-action btn-delete" 
                                                    onclick="return confirm('Yakin ingin menghapus murid ini?')" 
                                                    title="Hapus Murid">
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
                    <i class="bi bi-people"></i>
                    <h4>Tidak Ada Data Murid</h4>
                    <p>Belum ada murid yang terdaftar atau sesuai dengan filter yang dipilih.</p>
                </div>
            @endif
        </div>

        <!-- Pagination (jika menggunakan paginate) -->
        @if(method_exists($murid, 'links'))
            <div class="d-flex justify-content-center mt-4">
                {{ $murid->appends(request()->query())->links() }}
            </div>
        @endif

        <!-- Action Buttons -->
        @if($murid->count() > 0)
        <div class="text-center mt-4">
            <a href="{{ route('admin.tambahMurid') }}" class="btn btn-primary btn-lg me-2">
                <i class="bi bi-plus-circle me-2"></i>
                Tambah Murid Baru
            </a>
        </div>
        @endif
    </div>

    <script>
        // Search functionality (client-side untuk real-time search)
        document.getElementById('searchInput').addEventListener('input', function(e) {
            const searchTerm = e.target.value.toLowerCase();
            if (searchTerm.length === 0 || searchTerm.length >= 2) { // Search setelah 2 karakter
                filterTable(searchTerm);
            }
        });
        
        function filterTable(searchTerm) {
            const tbody = document.querySelector('tbody');
            if (!tbody) return;
            
            const rows = tbody.getElementsByTagName('tr');
            let visibleCount = 0;
            
            for (let i = 0; i < rows.length; i++) {
                const row = rows[i];
                if (row.cells.length < 5) continue;
                
                const noReg = row.cells[0].textContent.toLowerCase();
                const namaMurid = row.cells[1].textContent.toLowerCase();
                const namaUnit = row.cells[2].textContent.toLowerCase();
                const tingkatSabuk = row.cells[3].textContent.toLowerCase();
                
                const isVisible = searchTerm === '' || 
                    noReg.includes(searchTerm) || 
                    namaMurid.includes(searchTerm) || 
                    namaUnit.includes(searchTerm) ||
                    tingkatSabuk.includes(searchTerm);
                
                if (isVisible) {
                    row.style.display = '';
                    visibleCount++;
                } else {
                    row.style.display = 'none';
                }
            }
            
            // Tampilkan pesan jika tidak ada hasil
            showNoResultsMessage(visibleCount === 0 && searchTerm !== '');
        }
        
        function showNoResultsMessage(show) {
            let noResultsRow = document.getElementById('no-results-row');
            
            if (show && !noResultsRow) {
                const tbody = document.querySelector('tbody');
                noResultsRow = document.createElement('tr');
                noResultsRow.id = 'no-results-row';
                noResultsRow.innerHTML = `
                    <td colspan="6" class="text-center py-4">
                        <i class="bi bi-search"></i>
                        <div class="mt-2">Tidak ada hasil yang sesuai dengan pencarian</div>
                    </td>
                `;
                tbody.appendChild(noResultsRow);
            } else if (!show && noResultsRow) {
                noResultsRow.remove();
            }
        }

        // Reset search when form is reset
        document.addEventListener('DOMContentLoaded', function() {
            const resetButton = document.querySelector('a[href*="tampilMurid"]:not([href*="tambah"])');
            if (resetButton) {
                resetButton.addEventListener('click', function() {
                    document.getElementById('searchInput').value = '';
                });
            }
        });
    </script>
@endsection