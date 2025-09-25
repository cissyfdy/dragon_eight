@extends('layouts.murid')

@section('title', 'Tagihan Iuran Saya')

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
        .fee-card {
            border-left: 4px solid #28a745;
            margin-bottom: 1rem;
        }
        .fee-header {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            color: white;
            padding: 1.5rem;
            border-radius: 0.375rem 0.375rem 0 0;
        }
        .status-badge {
            font-size: 0.875rem;
            padding: 0.375rem 0.75rem;
        }
        .fee-summary-card {
            border: none;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
            transition: all 0.15s ease-in-out;
        }
        .fee-summary-card:hover {
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
            transform: translateY(-2px);
        }
        .amount-unpaid {
            color: #dc3545;
            font-weight: bold;
        }
        .amount-paid {
            color: #28a745;
            font-weight: bold;
        }
        .month-year {
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
                <div class="fee-header mb-4">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h1 class="mb-2">
                                <i class="bi bi-credit-card me-3"></i>
                                Tagihan Iuran Saya
                            </h1>
                            <p class="mb-0 opacity-75">Lihat dan kelola pembayaran iuran bulanan Anda</p>
                        </div>
                        <div class="col-md-4 text-end">
                            <button class="btn btn-light" onclick="loadIuranMurid()">
                                <i class="bi bi-arrow-clockwise me-1"></i>Refresh
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Summary Cards -->
                <div class="row mb-4">
                    <div class="col-lg-3 col-md-6 mb-3">
                        <div class="card fee-summary-card text-center border-primary">
                            <div class="card-body">
                                <i class="bi bi-receipt text-primary" style="font-size: 2rem;"></i>
                                <h5 class="card-title mt-2">Total Tagihan</h5>
                                <h3 class="text-primary" id="totalTagihan">0</h3>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 mb-3">
                        <div class="card fee-summary-card text-center border-success">
                            <div class="card-body">
                                <i class="bi bi-check-circle text-success" style="font-size: 2rem;"></i>
                                <h5 class="card-title mt-2">Sudah Lunas</h5>
                                <h3 class="text-success" id="sudahLunas">0</h3>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 mb-3">
                        <div class="card fee-summary-card text-center border-danger">
                            <div class="card-body">
                                <i class="bi bi-exclamation-triangle text-danger" style="font-size: 2rem;"></i>
                                <h5 class="card-title mt-2">Belum Lunas</h5>
                                <h3 class="text-danger" id="belumLunas">0</h3>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 mb-3">
                        <div class="card fee-summary-card text-center border-warning">
                            <div class="card-body">
                                <i class="bi bi-currency-dollar text-warning" style="font-size: 2rem;"></i>
                                <h5 class="card-title mt-2">Total Tunggakan</h5>
                                <h3 class="text-warning" id="totalTunggakan">Rp 0</h3>
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
                                <label class="form-label">Status</label>
                                <select class="form-select" id="filterStatus" onchange="applyFilters()">
                                    <option value="">Semua Status</option>
                                    <option value="Lunas">Lunas</option>
                                    <option value="Belum Lunas">Belum Lunas</option>
                                </select>
                            </div>
                            <div class="col-md-6 d-flex align-items-end">
                                <button class="btn btn-outline-secondary me-2" onclick="resetFilters()">
                                    <i class="bi bi-x-circle me-1"></i>Reset
                                </button>
                                <button class="btn btn-primary" onclick="exportData()">
                                    <i class="bi bi-download me-1"></i>Export
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tagihan Iuran Table -->
                <div class="fee-card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th><i class="bi bi-calendar me-1"></i>Bulan/Tahun</th>
                                        <th><i class="bi bi-currency-dollar me-1"></i>Nominal</th>
                                        <th><i class="bi bi-check-circle me-1"></i>Status</th>
                                        <th><i class="bi bi-calendar-event me-1"></i>Tanggal Bayar</th>
                                        <th><i class="bi bi-clock-history me-1"></i>Terlambat</th>
                                        <th><i class="bi bi-gear me-1"></i>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody id="iuranTableBody">
                                    <tr>
                                        <td colspan="6" class="loading">
                                            <div class="spinner-border spinner-border-sm me-2" role="status">
                                                <span class="visually-hidden">Loading...</span>
                                            </div>
                                            Memuat data iuran...
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Empty State -->
                <div id="emptyState" class="text-center py-5" style="display: none;">
                    <i class="bi bi-receipt text-muted" style="font-size: 4rem;"></i>
                    <h4 class="text-muted mt-3">Belum Ada Tagihan Iuran</h4>
                    <p class="text-muted">Belum ada tagihan iuran yang tercatat untuk akun Anda.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Payment Confirmation Modal -->
    <div class="modal fade" id="paymentModal" tabindex="-1" aria-labelledby="paymentModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="paymentModalLabel">Konfirmasi Pembayaran</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="paymentForm">
                        <div class="mb-3">
                            <label class="form-label">Bulan/Tahun</label>
                            <input type="text" class="form-control" id="paymentPeriod" readonly>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Nominal</label>
                            <input type="text" class="form-control" id="paymentAmount" readonly>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Tanggal Bayar</label>
                            <input type="date" class="form-control" id="paymentDate" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Catatan (Opsional)</label>
                            <textarea class="form-control" id="paymentNote" rows="2" placeholder="Tambahkan catatan pembayaran..."></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-success" onclick="confirmPayment()">Konfirmasi Pembayaran</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Get CSRF token
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        
        // Get murid_id from authenticated user
        const muridId = '{{ auth()->user()->murid->murid_id ?? "" }}';
        
        let allIuranData = [];
        let filteredData = [];
        let currentPaymentId = null;

        // Load data when page loads
        document.addEventListener('DOMContentLoaded', function() {
            if (muridId) {
                loadIuranMurid();
            } else {
                showError('Data murid tidak ditemukan. Silakan hubungi administrator.');
            }
        });

        function loadIuranMurid() {
            const tableBody = document.getElementById('iuranTableBody');
            const emptyState = document.getElementById('emptyState');
            
            // Show loading state
            tableBody.innerHTML = `
                <tr>
                    <td colspan="6" class="loading">
                        <div class="spinner-border spinner-border-sm me-2" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        Memuat data iuran...
                    </td>
                </tr>
            `;
            emptyState.style.display = 'none';

            // Fetch data from API
            fetch(`/murid/iuran/${muridId}`, {
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
                console.log('Iuran data received:', data);
                allIuranData = data;
                filteredData = data;
                displayIuranData(data);
                updateSummaryCards(data);
                populateYearFilter(data);
            })
            .catch(error => {
                console.error('Error loading iuran data:', error);
                showError('Gagal memuat data iuran: ' + error.message);
            });
        }

        function displayIuranData(iuranData) {
            const tableBody = document.getElementById('iuranTableBody');
            const emptyState = document.getElementById('emptyState');

            if (!iuranData || iuranData.length === 0) {
                tableBody.innerHTML = '';
                emptyState.style.display = 'block';
                return;
            }

            emptyState.style.display = 'none';
            
            const rows = iuranData.map(iuran => {
                const statusBadge = getStatusBadge(iuran.status);
                const nominalFormatted = formatCurrency(iuran.nominal);
                const tanggalBayar = iuran.tanggal_bayar ? formatDate(iuran.tanggal_bayar) : '-';
                const isOverdue = checkOverdue(iuran);
                const overdueText = isOverdue && iuran.status === 'Belum Lunas' ? 
                    '<span class="badge bg-danger">Ya</span>' : 
                    '<span class="badge bg-success">Tidak</span>';

                return `
                    <tr>
                        <td>
                            <div class="month-year">${iuran.bulan} ${iuran.tahun}</div>
                        </td>
                        <td>
                            <span class="${iuran.status === 'Lunas' ? 'amount-paid' : 'amount-unpaid'}">${nominalFormatted}</span>
                        </td>
                        <td>${statusBadge}</td>
                        <td>${tanggalBayar}</td>
                        <td>${overdueText}</td>
                        <td>
                            ${iuran.status === 'Belum Lunas' ? 
                                `<button class="btn btn-sm btn-success" onclick="showPaymentModal(${iuran.iuran_id}, '${iuran.bulan} ${iuran.tahun}', ${iuran.nominal})">
                                    <i class="bi bi-credit-card me-1"></i>Bayar
                                </button>` : 
                                `<span class="text-muted">Sudah Lunas</span>`
                            }
                        </td>
                    </tr>
                `;
            }).join('');

            tableBody.innerHTML = rows;
        }

        function getStatusBadge(status) {
            const badges = {
                'Lunas': '<span class="badge bg-success status-badge">Lunas</span>',
                'Belum Lunas': '<span class="badge bg-danger status-badge">Belum Lunas</span>'
            };
            return badges[status] || '<span class="badge bg-secondary status-badge">Unknown</span>';
        }

        function updateSummaryCards(iuranData) {
            const total = iuranData.length;
            const lunas = iuranData.filter(i => i.status === 'Lunas').length;
            const belumLunas = iuranData.filter(i => i.status === 'Belum Lunas').length;
            const totalTunggakan = iuranData
                .filter(i => i.status === 'Belum Lunas')
                .reduce((sum, i) => sum + parseFloat(i.nominal || 0), 0);

            document.getElementById('totalTagihan').textContent = total;
            document.getElementById('sudahLunas').textContent = lunas;
            document.getElementById('belumLunas').textContent = belumLunas;
            document.getElementById('totalTunggakan').textContent = formatCurrency(totalTunggakan);
        }

        function populateYearFilter(iuranData) {
            const years = [...new Set(iuranData.map(i => i.tahun))].sort((a, b) => b - a);
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
            const statusFilter = document.getElementById('filterStatus').value;

            filteredData = allIuranData.filter(iuran => {
                const tahunMatch = !tahunFilter || iuran.tahun.toString() === tahunFilter;
                const statusMatch = !statusFilter || iuran.status === statusFilter;
                return tahunMatch && statusMatch;
            });

            displayIuranData(filteredData);
            updateSummaryCards(filteredData);
        }

        function resetFilters() {
            document.getElementById('filterTahun').value = '';
            document.getElementById('filterStatus').value = '';
            filteredData = allIuranData;
            displayIuranData(filteredData);
            updateSummaryCards(filteredData);
        }

        function showPaymentModal(iuranId, period, amount) {
            currentPaymentId = iuranId;
            document.getElementById('paymentPeriod').value = period;
            document.getElementById('paymentAmount').value = formatCurrency(amount);
            document.getElementById('paymentDate').value = new Date().toISOString().split('T')[0];
            document.getElementById('paymentNote').value = '';
            
            const modal = new bootstrap.Modal(document.getElementById('paymentModal'));
            modal.show();
        }

        function confirmPayment() {
            if (!currentPaymentId) return;

            const paymentDate = document.getElementById('paymentDate').value;
            const paymentNote = document.getElementById('paymentNote').value;

            if (!paymentDate) {
                alert('Tanggal bayar harus diisi!');
                return;
            }

            const formData = {
                tanggal_bayar: paymentDate,
                catatan: paymentNote
            };

            fetch(`/iuran/bayar/${currentPaymentId}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify(formData)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Hide modal
                    const modal = bootstrap.Modal.getInstance(document.getElementById('paymentModal'));
                    modal.hide();
                    
                    // Show success message
                    alert('Pembayaran berhasil dikonfirmasi!');
                    
                    // Reload data
                    loadIuranMurid();
                } else {
                    alert('Gagal mengkonfirmasi pembayaran: ' + (data.message || 'Unknown error'));
                }
            })
            .catch(error => {
                console.error('Error confirming payment:', error);
                alert('Terjadi kesalahan saat mengkonfirmasi pembayaran.');
            });
        }

        function exportData() {
            const tahunFilter = document.getElementById('filterTahun').value;
            const statusFilter = document.getElementById('filterStatus').value;
            
            let url = `/iuran/export?murid_id=${muridId}`;
            if (tahunFilter) url += `&tahun=${tahunFilter}`;
            if (statusFilter) url += `&status=${statusFilter}`;
            
            window.open(url, '_blank');
        }

        function checkOverdue(iuran) {
            if (iuran.status === 'Lunas') return false;
            
            const currentDate = new Date();
            const currentYear = currentDate.getFullYear();
            const currentMonth = currentDate.getMonth() + 1; // JavaScript months are 0-indexed
            
            const months = {
                'Januari': 1, 'Februari': 2, 'Maret': 3, 'April': 4,
                'Mei': 5, 'Juni': 6, 'Juli': 7, 'Agustus': 8,
                'September': 9, 'Oktober': 10, 'November': 11, 'Desember': 12
            };
            
            const iuranMonth = months[iuran.bulan];
            const iuranYear = parseInt(iuran.tahun);
            
            return (iuranYear < currentYear) || (iuranYear === currentYear && iuranMonth < currentMonth);
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

        function formatCurrency(amount) {
            if (!amount) return 'Rp 0';
            
            return new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR',
                minimumFractionDigits: 0
            }).format(amount);
        }

        function showError(message) {
            const tableBody = document.getElementById('iuranTableBody');
            const emptyState = document.getElementById('emptyState');
            
            tableBody.innerHTML = `
                <tr>
                    <td colspan="6" class="error-message">
                        <i class="bi bi-exclamation-triangle me-2"></i>
                        ${message}
                    </td>
                </tr>
            `;
            emptyState.style.display = 'none';
        }

        // Auto-refresh every 5 minutes
        setInterval(loadIuranMurid, 5 * 60 * 1000);
    </script>
@endsection