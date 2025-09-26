@extends('layouts.murid')

@section('title', 'Riwayat Absensi Saya')

@section('content')
    <style>
        .loading {
            text-align: center;
            color: #6c757d;
        }
        .error-message {
            color: #dc3545;
            text-align: center;
        }
        .absensi-card {
            border-left: 4px solid #007bff;
            margin-bottom: 1rem;
        }
        .status-badge {
            font-size: 0.875rem;
            padding: 0.375rem 0.75rem;
        }
        .summary-card {
            border: none;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
            transition: all 0.15s ease-in-out;
        }
        .summary-card:hover {
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
            transform: translateY(-2px);
        }
        .progress-custom {
            height: 25px;
            border-radius: 10px;
            box-shadow: inset 0 1px 3px rgba(0, 0, 0, 0.2);
        }
        .progress-bar-custom {
            border-radius: 10px;
            transition: width 0.6s ease;
        }
        .date-info {
            background: linear-gradient(45deg, #007bff, #6610f2);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            font-weight: bold;
        }
    </style>

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <!-- Header Section -->
                <div class="header-section mb-4">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h1 class="mb-2">
                                <i class="bi bi-calendar-check me-3"></i>
                                Riwayat Absensi Saya
                            </h1>
                            <p class="mb-0 opacity-75">Lihat riwayat kehadiran latihan Anda</p>
                        </div>
                        <div class="col-md-4 text-end">
                            <button class="btn btn-light" onclick="loadAbsensiMurid()">
                                <i class="bi bi-arrow-clockwise me-1"></i>Refresh
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Summary Cards -->
                <div class="row mb-4">
                    <div class="col-lg-3 col-md-6 mb-3">
                        <div class="card summary-card text-center border-success">
                            <div class="card-body">
                                <i class="bi bi-check-circle text-success" style="font-size: 2rem;"></i>
                                <h5 class="card-title mt-2">Hadir</h5>
                                <h3 class="text-success" id="totalHadir">0</h3>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 mb-3">
                        <div class="card summary-card text-center border-danger">
                            <div class="card-body">
                                <i class="bi bi-x-circle text-danger" style="font-size: 2rem;"></i>
                                <h5 class="card-title mt-2">Tidak Hadir</h5>
                                <h3 class="text-danger" id="totalTidakHadir">0</h3>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 mb-3">
                        <div class="card summary-card text-center border-warning">
                            <div class="card-body">
                                <i class="bi bi-exclamation-triangle text-warning" style="font-size: 2rem;"></i>
                                <h5 class="card-title mt-2">Izin</h5>
                                <h3 class="text-warning" id="totalIzin">0</h3>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 mb-3">
                        <div class="card summary-card text-center border-info">
                            <div class="card-body">
                                <i class="bi bi-thermometer-half text-info" style="font-size: 2rem;"></i>
                                <h5 class="card-title mt-2">Sakit</h5>
                                <h3 class="text-info" id="totalSakit">0</h3>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Persentase Kehadiran -->
                <div class="row mb-4">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body text-center">
                                <h5 class="card-title">Persentase Kehadiran</h5>
                                <div class="progress progress-custom mb-2">
                                    <div class="progress-bar progress-bar-custom bg-success" role="progressbar" 
                                         style="width: 0%" id="progressKehadiran">
                                        <span id="textPersentase">0%</span>
                                    </div>
                                </div>
                                <small class="text-muted">Dihitung dari total latihan yang diikuti</small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Filter Section -->
                <div class="card mb-4">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3">
                                <label class="form-label">Tahun</label>
                                <select class="form-select" id="filterTahun" onchange="applyFilters()">
                                    <option value="">Semua Tahun</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Bulan</label>
                                <select class="form-select" id="filterBulan" onchange="applyFilters()">
                                    <option value="">Semua Bulan</option>
                                    <option value="01">Januari</option>
                                    <option value="02">Februari</option>
                                    <option value="03">Maret</option>
                                    <option value="04">April</option>
                                    <option value="05">Mei</option>
                                    <option value="06">Juni</option>
                                    <option value="07">Juli</option>
                                    <option value="08">Agustus</option>
                                    <option value="09">September</option>
                                    <option value="10">Oktober</option>
                                    <option value="11">November</option>
                                    <option value="12">Desember</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Status</label>
                                <select class="form-select" id="filterStatus" onchange="applyFilters()">
                                    <option value="">Semua Status</option>
                                    <option value="hadir">Hadir</option>
                                    <option value="tidak_hadir">Tidak Hadir</option>
                                    <option value="izin">Izin</option>
                                    <option value="sakit">Sakit</option>
                                </select>
                            </div>
                            <div class="col-md-3 d-flex align-items-end">
                                <button class="btn btn-outline-secondary me-2" onclick="resetFilters()">
                                    <i class="bi bi-x-circle me-1"></i>Reset
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Absensi Table -->
                <div class="card absensi-card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th><i class="bi bi-calendar me-1"></i>Tanggal</th>
                                        <th><i class="bi bi-calendar-day me-1"></i>Hari</th>
                                        <th><i class="bi bi-clock me-1"></i>Jam</th>
                                        <th><i class="bi bi-building me-1"></i>Unit</th>
                                        <th><i class="bi bi-person me-1"></i>Pelatih</th>
                                        <th><i class="bi bi-check-circle me-1"></i>Status</th>
                                        <th><i class="bi bi-chat-dots me-1"></i>Catatan</th>
                                    </tr>
                                </thead>
                                <tbody id="absensiTableBody">
                                    <tr>
                                        <td colspan="7" class="loading">
                                            <div class="spinner-border spinner-border-sm me-2" role="status">
                                                <span class="visually-hidden">Loading...</span>
                                            </div>
                                            Memuat data absensi...
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Empty State -->
                <div id="emptyState" class="text-center py-5" style="display: none;">
                    <i class="bi bi-calendar-x text-muted" style="font-size: 4rem;"></i>
                    <h4 class="text-muted mt-3">Belum Ada Data Absensi</h4>
                    <p class="text-muted">Belum ada data kehadiran yang tercatat untuk akun Anda.</p>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Get CSRF token
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        
        // Get murid_id from authenticated user
        const muridId = '{{ auth()->user()->murid->murid_id ?? "" }}';
        
        let allAbsensiData = [];
        let filteredData = [];

        // Load data when page loads
        document.addEventListener('DOMContentLoaded', function() {
            if (muridId) {
                loadAbsensiMurid();
                setDefaultFilters();
            } else {
                showError('Data murid tidak ditemukan. Silakan hubungi administrator.');
            }
        });

        function setDefaultFilters() {
            const currentDate = new Date();
            const currentYear = currentDate.getFullYear();
            const currentMonth = String(currentDate.getMonth() + 1).padStart(2, '0');
            
            document.getElementById('filterTahun').value = currentYear;
            document.getElementById('filterBulan').value = currentMonth;
        }

        function loadAbsensiMurid() {
            const tableBody = document.getElementById('absensiTableBody');
            const emptyState = document.getElementById('emptyState');
            
            // Show loading state
            tableBody.innerHTML = `
                <tr>
                    <td colspan="7" class="loading">
                        <div class="spinner-border spinner-border-sm me-2" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        Memuat data absensi...
                    </td>
                </tr>
            `;
            emptyState.style.display = 'none';

            // Fetch data from the correct API endpoint
            fetch(`/api/absensi/${muridId}`, {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                }
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(result => {
                console.log('Absensi data received:', result);
                
                if (result.success && result.data) {
                    allAbsensiData = result.data;
                    filteredData = result.data;
                    displayAbsensiData(result.data);
                    updateSummaryCards(result.data);
                    populateYearFilter(result.data);
                    applyFilters();
                } else {
                    throw new Error(result.error || 'Invalid response format');
                }
            })
            .catch(error => {
                console.error('Error loading absensi data:', error);
                showError('Gagal memuat data absensi: ' + error.message);
            });
        }

        function displayAbsensiData(absensiData) {
            const tableBody = document.getElementById('absensiTableBody');
            const emptyState = document.getElementById('emptyState');

            if (!absensiData || absensiData.length === 0) {
                tableBody.innerHTML = '';
                emptyState.style.display = 'block';
                return;
            }

            emptyState.style.display = 'none';
            
            const rows = absensiData.map(absen => {
                const statusBadge = getStatusBadge(absen.status_kehadiran);
                const tanggalFormatted = formatDate(absen.tanggal_latihan);
                const hari = absen.hari || getHariFromDate(absen.tanggal_latihan);
                const jamMulai = absen.jam_mulai ? formatTime(absen.jam_mulai) : '-';
                const jamSelesai = absen.jam_selesai ? formatTime(absen.jam_selesai) : '-';
                const jam = jamMulai !== '-' && jamSelesai !== '-' ? `${jamMulai} - ${jamSelesai}` : '-';
                const unit = absen.nama_unit || '-';
                const pelatih = absen.nama_pelatih || '-';
                const catatan = absen.catatan || '-';

                return `
                    <tr data-tanggal="${absen.tanggal_latihan}" data-status="${absen.status_kehadiran}">
                        <td>
                            <div class="date-info">${tanggalFormatted}</div>
                        </td>
                        <td>${hari}</td>
                        <td>${jam}</td>
                        <td>${unit}</td>
                        <td>${pelatih}</td>
                        <td>${statusBadge}</td>
                        <td>${catatan}</td>
                    </tr>
                `;
            }).join('');

            tableBody.innerHTML = rows;
        }

        function getStatusBadge(status) {
            const badges = {
                'hadir': '<span class="badge bg-success status-badge"><i class="bi bi-check"></i> Hadir</span>',
                'tidak_hadir': '<span class="badge bg-danger status-badge"><i class="bi bi-x"></i> Tidak Hadir</span>',
                'izin': '<span class="badge bg-warning status-badge"><i class="bi bi-exclamation-triangle"></i> Izin</span>',
                'sakit': '<span class="badge bg-info status-badge"><i class="bi bi-thermometer-half"></i> Sakit</span>'
            };
            return badges[status] || '<span class="badge bg-secondary status-badge">-</span>';
        }

        function updateSummaryCards(absensiData) {
            const hadir = absensiData.filter(a => a.status_kehadiran === 'hadir').length;
            const tidakHadir = absensiData.filter(a => a.status_kehadiran === 'tidak_hadir').length;
            const izin = absensiData.filter(a => a.status_kehadiran === 'izin').length;
            const sakit = absensiData.filter(a => a.status_kehadiran === 'sakit').length;
            const total = absensiData.length;

            document.getElementById('totalHadir').textContent = hadir;
            document.getElementById('totalTidakHadir').textContent = tidakHadir;
            document.getElementById('totalIzin').textContent = izin;
            document.getElementById('totalSakit').textContent = sakit;

            // Update progress bar
            const persentase = total > 0 ? Math.round((hadir / total) * 100) : 0;
            const progressBar = document.getElementById('progressKehadiran');
            const textPersentase = document.getElementById('textPersentase');
            
            progressBar.style.width = persentase + '%';
            textPersentase.textContent = persentase + '%';
            
            // Change color based on percentage
            progressBar.className = 'progress-bar progress-bar-custom';
            if (persentase >= 80) {
                progressBar.classList.add('bg-success');
            } else if (persentase >= 60) {
                progressBar.classList.add('bg-warning');
            } else {
                progressBar.classList.add('bg-danger');
            }
        }

        function populateYearFilter(absensiData) {
            const years = [...new Set(absensiData.map(a => new Date(a.tanggal_latihan).getFullYear()))].sort((a, b) => b - a);
            const yearFilter = document.getElementById('filterTahun');
            
            // Clear existing options except the first one
            yearFilter.innerHTML = '<option value="">Semua Tahun</option>';
            
            years.forEach(year => {
                const option = document.createElement('option');
                option.value = year;
                option.textContent = year;
                yearFilter.appendChild(option);
            });
        }

        function applyFilters() {
            const tahunFilter = document.getElementById('filterTahun').value;
            const bulanFilter = document.getElementById('filterBulan').value;
            const statusFilter = document.getElementById('filterStatus').value;

            filteredData = allAbsensiData.filter(absen => {
                const tanggal = new Date(absen.tanggal_latihan);
                const tahun = tanggal.getFullYear().toString();
                const bulan = String(tanggal.getMonth() + 1).padStart(2, '0');
                
                const tahunMatch = !tahunFilter || tahun === tahunFilter;
                const bulanMatch = !bulanFilter || bulan === bulanFilter;
                const statusMatch = !statusFilter || absen.status_kehadiran === statusFilter;
                
                return tahunMatch && bulanMatch && statusMatch;
            });

            displayAbsensiData(filteredData);
            updateSummaryCards(filteredData);
        }

        function resetFilters() {
            document.getElementById('filterTahun').value = '';
            document.getElementById('filterBulan').value = '';
            document.getElementById('filterStatus').value = '';
            filteredData = allAbsensiData;
            displayAbsensiData(filteredData);
            updateSummaryCards(filteredData);
        }

        function getHariFromDate(dateString) {
            const days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
            const date = new Date(dateString);
            return days[date.getDay()];
        }

        function formatDate(dateString) {
            if (!dateString) return 'N/A';
            
            const date = new Date(dateString);
            const options = { 
                year: 'numeric', 
                month: 'short', 
                day: 'numeric' 
            };
            return date.toLocaleDateString('id-ID', options);
        }

        function formatTime(timeString) {
            if (!timeString) return '-';
            return timeString.substring(0, 5);
        }

        function showError(message) {
            const tableBody = document.getElementById('absensiTableBody');
            const emptyState = document.getElementById('emptyState');
            
            tableBody.innerHTML = `
                <tr>
                    <td colspan="7" class="error-message">
                        <i class="bi bi-exclamation-triangle me-2"></i>
                        ${message}
                    </td>
                </tr>
            `;
            emptyState.style.display = 'none';
        }

        // Auto-refresh every 5 minutes
        setInterval(loadAbsensiMurid, 5 * 60 * 1000);
    </script>
@endsection