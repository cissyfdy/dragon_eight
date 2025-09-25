@extends('layouts.murid')

@section('title', 'Jadwal Ujian Saya')

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
        .exam-card {
            border-left: 4px solid #007bff;
            margin-bottom: 1rem;
        }
        .exam-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 1.5rem;
            border-radius: 0.375rem 0.375rem 0 0;
        }
        .status-badge {
            font-size: 0.875rem;
            padding: 0.375rem 0.75rem;
        }
    </style>

    <!-- Pastikan CSRF token tersedia -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <!-- Header Section -->
                <div class="exam-header mb-4">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h1 class="mb-2">
                                <i class="bi bi-trophy me-3"></i>
                                Jadwal Ujian Saya
                            </h1>
                            <p class="mb-0 opacity-75">Lihat jadwal ujian yang sudah Anda daftarkan</p>
                        </div>
                        <div class="col-md-4 text-end">
                            <button class="btn btn-light" onclick="loadUjianMurid()">
                                <i class="bi bi-arrow-clockwise me-1"></i>Refresh
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Jadwal Ujian Table -->
                <div class="exam-card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th><i class="bi bi-card-text me-1"></i>Nama Ujian</th>
                                        <th><i class="bi bi-calendar-event me-1"></i>Tanggal</th>
                                        <th><i class="bi bi-clock me-1"></i>Waktu</th>
                                        <th><i class="bi bi-award me-1"></i>Tingkat Sabuk</th>
                                        <th><i class="bi bi-check-circle me-1"></i>Status</th>
                                        <th><i class="bi bi-credit-card me-1"></i>Pembayaran</th>
                                        <th><i class="bi bi-geo-alt me-1"></i>Unit</th>
                                        <th><i class="bi bi-person me-1"></i>Pelatih</th>
                                    </tr>
                                </thead>
                                <tbody id="ujianTableBody">
                                    <tr>
                                        <td colspan="8" class="loading">
                                            <div class="spinner-border spinner-border-sm me-2" role="status">
                                                <span class="visually-hidden">Loading...</span>
                                            </div>
                                            Memuat data ujian...
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Info Cards -->
                <div class="row mt-4">
                    <div class="col-md-3">
                        <div class="card text-center border-primary">
                            <div class="card-body">
                                <i class="bi bi-calendar-check text-primary" style="font-size: 2rem;"></i>
                                <h5 class="card-title mt-2">Total Ujian</h5>
                                <h3 class="text-primary" id="totalUjian">0</h3>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card text-center border-success">
                            <div class="card-body">
                                <i class="bi bi-check-circle text-success" style="font-size: 2rem;"></i>
                                <h5 class="card-title mt-2">Diterima</h5>
                                <h3 class="text-success" id="ujianDiterima">0</h3>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card text-center border-warning">
                            <div class="card-body">
                                <i class="bi bi-clock-history text-warning" style="font-size: 2rem;"></i>
                                <h5 class="card-title mt-2">Menunggu</h5>
                                <h3 class="text-warning" id="ujianMenunggu">0</h3>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card text-center border-info">
                            <div class="card-body">
                                <i class="bi bi-credit-card text-info" style="font-size: 2rem;"></i>
                                <h5 class="card-title mt-2">Sudah Bayar</h5>
                                <h3 class="text-info" id="ujianBayar">0</h3>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Empty State -->
                <div id="emptyState" class="text-center py-5" style="display: none;">
                    <i class="bi bi-calendar-x text-muted" style="font-size: 4rem;"></i>
                    <h4 class="text-muted mt-3">Belum Ada Ujian Terdaftar</h4>
                    <p class="text-muted">Anda belum mendaftarkan ujian apapun. Silakan hubungi pelatih untuk mendaftar ujian.</p>
                </div>
            </div>
        </div>
    </div>

    <script>
// Get CSRF token
const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

// Get murid_id from authenticated user (you'll need to pass this from the controller)
const muridId = '{{ auth()->user()->murid->murid_id ?? "" }}';

// Load ujian data when page loads
document.addEventListener('DOMContentLoaded', function() {
    if (muridId) {
        loadUjianMurid();
    } else {
        showError('Data murid tidak ditemukan. Silakan hubungi administrator.');
    }
});

function loadUjianMurid() {
    const tableBody = document.getElementById('ujianTableBody');
    const emptyState = document.getElementById('emptyState');
    
    // Show loading state
    tableBody.innerHTML = `
        <tr>
            <td colspan="8" class="loading">
                <div class="spinner-border spinner-border-sm me-2" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                Memuat data ujian...
            </td>
        </tr>
    `;
    emptyState.style.display = 'none';

    // Fetch data from API
    fetch(`/murid/ujian/${muridId}`, {
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
    .then(data => {
        console.log('Ujian data received:', data);
        displayUjianData(data);
        updateStatistics(data);
    })
    .catch(error => {
        console.error('Error loading ujian data:', error);
        showError('Gagal memuat data ujian: ' + error.message);
    });
}

function displayUjianData(ujianData) {
    const tableBody = document.getElementById('ujianTableBody');
    const emptyState = document.getElementById('emptyState');

    if (!ujianData || ujianData.length === 0) {
        tableBody.innerHTML = '';
        emptyState.style.display = 'block';
        return;
    }

    emptyState.style.display = 'none';
    
    const rows = ujianData.map(ujian => {
        const statusBadge = getStatusBadge(ujian.status_pendaftaran);
        const paymentBadge = getPaymentBadge(ujian.status_pembayaran);
        const tanggalFormatted = formatDate(ujian.tanggal_ujian);
        const waktu = `${ujian.waktu_mulai} - ${ujian.waktu_selesai}`;
        const biayaFormatted = formatCurrency(ujian.biaya_ujian);

        return `
            <tr>
                <td>
                    <div class="fw-bold">${ujian.nama_ujian}</div>
                    <small class="text-muted">Biaya: ${biayaFormatted}</small>
                </td>
                <td>
                    <span class="badge bg-light text-dark">${tanggalFormatted}</span>
                </td>
                <td>${waktu}</td>
                <td>
                    <span class="badge bg-secondary">${ujian.sabuk_dari} → ${ujian.sabuk_ke}</span>
                </td>
                <td>${statusBadge}</td>
                <td>${paymentBadge}</td>
                <td>
                    <div class="fw-semibold">${ujian.nama_unit || 'N/A'}</div>
                </td>
                <td>${ujian.nama_pelatih || 'N/A'}</td>
            </tr>
        `;
    }).join('');

    tableBody.innerHTML = rows;
}

function getStatusBadge(status) {
    const badges = {
        'terdaftar': '<span class="badge bg-warning status-badge">Terdaftar</span>',
        'diterima': '<span class="badge bg-success status-badge">Diterima</span>',
        'ditolak': '<span class="badge bg-danger status-badge">Ditolak</span>',
        'dibatalkan': '<span class="badge bg-secondary status-badge">Dibatalkan</span>'
    };
    return badges[status] || '<span class="badge bg-secondary status-badge">Unknown</span>';
}

function getPaymentBadge(status) {
    const badges = {
        'belum_bayar': '<span class="badge bg-warning status-badge">Belum Bayar</span>',
        'sudah_bayar': '<span class="badge bg-success status-badge">Sudah Bayar</span>',
        'refund': '<span class="badge bg-info status-badge">Refund</span>'
    };
    return badges[status] || '<span class="badge bg-secondary status-badge">Unknown</span>';
}

function updateStatistics(ujianData) {
    const totalUjian = ujianData.length;
    const ujianDiterima = ujianData.filter(u => u.status_pendaftaran === 'diterima').length;
    const ujianMenunggu = ujianData.filter(u => u.status_pendaftaran === 'terdaftar').length;
    const ujianBayar = ujianData.filter(u => u.status_pembayaran === 'sudah_bayar').length;

    document.getElementById('totalUjian').textContent = totalUjian;
    document.getElementById('ujianDiterima').textContent = ujianDiterima;
    document.getElementById('ujianMenunggu').textContent = ujianMenunggu;
    document.getElementById('ujianBayar').textContent = ujianBayar;
}

function showError(message) {
    const tableBody = document.getElementById('ujianTableBody');
    const emptyState = document.getElementById('emptyState');
    
    tableBody.innerHTML = `
        <tr>
            <td colspan="8" class="error-message">
                <i class="bi bi-exclamation-triangle me-2"></i>
                ${message}
            </td>
        </tr>
    `;
    emptyState.style.display = 'none';
}

function formatDate(dateString) {
    if (!dateString) return 'N/A';
    
    const date = new Date(dateString);
    const options = { 
        weekday: 'short', 
        year: 'numeric', 
        month: 'short', 
        day: 'numeric' 
    };
    return date.toLocaleDateString('id-ID', options);
}

function formatCurrency(amount) {
    if (!amount) return 'Rp 0';
    
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        minimumFractionDigits: 0
    }).format(amount);
}

// Add refresh functionality
function refreshData() {
    loadUjianMurid();
}

// Auto-refresh every 5 minutes
setInterval(loadUjianMurid, 5 * 60 * 1000);
</script>
@endsection