@extends('layout')

@section('konten')
    <style>
        .loading {
            text-align: center;
            color: #6c757d;
        }
        .error-message {
            color: #dc3545;
            text-align: center;
        }
    </style>
    <title>Profil Murid</title>

    <!-- Pastikan CSRF token tersedia -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">

                <!-- Header Section & Informasi Murid -->
                <div class="header-section">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h1 class="mb-2">
                                <i class="bi bi-person me-3"></i>
                                Profil {{ $murid->nama_murid }}
                            </h1>
                            <p class="mb-0 opacity-75"><strong>Taekwondo {{ $murid->unit->nama_unit }}</strong></p>
                            <p class="mb-0 opacity-75"><strong>Alamat Unit:</strong> {{ $murid->unit->alamat }}</p>
                        </div>
                    </div>
                </div>

                <!-- Profile Card -->
                <div class="card profile-card mb-4">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h3 class="mb-0"><i class="bi bi-person-vcard"></i> Informasi Murid</h3>
                        </div>
                        <div class="row">
                            <div class="col-md-6">                                
                                <div class="profile-info">
                                    
                                    <div>
                                        <i class="bi bi-person-fill"></i>
                                        <strong>Nama Lengkap</strong><br>
                                        <span>{{ $murid->nama_murid }}</span>
                                    </div>
                                </div>
                                
                                <div class="profile-info">
                                    <div>
                                        <i class="bi bi-credit-card-2-front-fill"></i>
                                        <strong>No. Register</strong><br>
                                        <span>{{ $murid->nomor_register }}</span>
                                    </div>
                                </div>
                                
                                <div class="profile-info">
                                    <div>
                                        <i class="bi bi-calendar-fill"></i>
                                        <strong>Tanggal Lahir</strong><br>
                                        <span>{{ \Carbon\Carbon::parse($murid->tanggal_lahir)->format('d/m/Y') }}</span>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="profile-info">
                                    <div>
                                        <i class="bi bi-geo-alt-fill"></i>
                                        <strong>Alamat Rumah</strong><br>
                                        <span>{{ $murid->alamat }}</span>
                                    </div>
                                </div>
                                
                                <div class="profile-info">
                                    <div>
                                        <i class="bi bi-award-fill"></i>
                                        <strong>Tingkat Sabuk Saat Ini</strong><br>
                                        <span>{{ $murid->tingkat_sabuk }}</span>
                                    </div>
                                </div>
                                
                                <div class="profile-info">
                                    <div>
                                        <i class="bi bi-telephone-fill"></i>
                                        <strong>No. HP</strong><br>
                                        <span>{{ $murid->no_hp }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Jadwal Latihan -->
                <div class="table-container mb-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h4 class="mb-0"><i class="bi bi-calendar3 me-2"></i>Jadwal Latihan {{ $murid->nama_murid }}</h4>
                        <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#tambahJadwalModal">
                            <i class="bi bi-plus-circle me-1"></i>Tambah Jadwal
                        </button>
                    </div>
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>Hari</th>
                                <th>Jam</th>
                                <th>Pelatih</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="jadwalTableBody">
                            <tr>
                                <td colspan="4" class="loading">Memuat data jadwal...</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Pendaftaran Ujian -->
                <div class="table-container mb-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h4 class="mb-0"><i class="bi bi-trophy me-2"></i>Jadwal Ujian {{ $murid->nama_murid }}</h4>
                        <button class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#tambahUjianModal">
                            <i class="bi bi-plus-circle me-1"></i>Daftar Ujian
                        </button>
                    </div>
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>Nama Ujian</th>
                                <th>Tanggal</th>
                                <th>Waktu</th>
                                <th>Sabuk</th>
                                <th>Status</th>
                                <th>Pembayaran</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="ujianTableBody">
                            <tr>
                                <td colspan="7" class="loading">Memuat data ujian...</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Tambah Jadwal -->
    <div class="modal fade" id="tambahJadwalModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Jadwal untuk {{ $murid->nama_murid }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div id="loadingMessage" style="display: none;">
                        <div class="text-center">
                            <div class="spinner-border" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                            <p>Menyimpan data...</p>
                        </div>
                    </div>
                    <form id="formTambahJadwal">
                        <div class="mb-3">
                            <label class="form-label">Pilih Jadwal</label>
                            <select class="form-select" id="selectJadwal" required>
                                <option value="">Memuat jadwal...</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Tanggal Daftar</label>
                            <input type="date" class="form-control" id="tanggalDaftar" 
                                   value="{{ date('Y-m-d') }}" required>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-primary" id="btnSimpan" onclick="simpanJadwal()">Simpan</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Tambah Ujian -->
    <div class="modal fade" id="tambahUjianModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Daftar Ujian untuk {{ $murid->nama_murid }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div id="loadingMessageUjian" style="display: none;">
                        <div class="text-center">
                            <div class="spinner-border" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                            <p>Menyimpan data...</p>
                        </div>
                    </div>
                    <form id="formTambahUjian">
                        <div class="mb-3">
                            <label class="form-label">Pilih Ujian</label>
                            <select class="form-select" id="selectUjian" required>
                                <option value="">Memuat ujian...</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Tanggal Daftar</label>
                            <input type="date" class="form-control" id="tanggalDaftarUjian" 
                                   value="{{ date('Y-m-d') }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Catatan Pendaftaran (Opsional)</label>
                            <textarea class="form-control" id="catatanPendaftaran" rows="3" 
                                      placeholder="Tambahkan catatan jika diperlukan..."></textarea>
                        </div>
                        <div class="alert alert-info" id="ujianInfo" style="display: none;">
                            <h6>Informasi Ujian:</h6>
                            <div id="ujianDetails"></div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-success" id="btnSimpanUjian" onclick="simpanUjian()">Daftar Ujian</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const csrfToken = document.querySelector('meta[name="csrf-token"]');
            if (!csrfToken) {
                console.error('CSRF token not found');
                alert('CSRF token tidak tersedia. Silakan refresh halaman.');
                return;
            }

            loadJadwalMurid();
            loadAvailableJadwal();
            loadUjianMurid();
            loadAvailableUjian();
        });

        // Load jadwal murid
        function loadJadwalMurid() {
            const muridId = '{{ $murid->murid_id }}';
            
            fetch(`/murid/jadwal/${muridId}`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    console.log('Jadwal murid data:', data); // Debug log
                    const tbody = document.getElementById('jadwalTableBody');
                    tbody.innerHTML = '';
                    
                    if (!data || data.length === 0) {
                        tbody.innerHTML = '<tr><td colspan="4" class="text-center text-muted">Belum ada jadwal latihan</td></tr>';
                        return;
                    }
                    
                    data.forEach(item => {
                        const row = `
                            <tr>
                                <td>${item.hari || 'N/A'}</td>
                                <td>${item.jam || 'N/A'}</td>
                                <td>${item.pelatih || 'N/A'}</td>
                                <td>
                                    <button class="btn btn-danger btn-sm" onclick="batalJadwal(${item.id})">
                                        <i class="bi bi-x-circle"></i> Batal
                                    </button>
                                </td>
                            </tr>
                        `;
                        tbody.innerHTML += row;
                    });
                })
                .catch(error => {
                    console.error('Error loading jadwal:', error);
                    document.getElementById('jadwalTableBody').innerHTML = 
                        '<tr><td colspan="4" class="text-center error-message">Error loading data: ' + error.message + '</td></tr>';
                });
        }

        // Load ujian murid
        function loadUjianMurid() {
            const muridId = '{{ $murid->murid_id }}';

            fetch(`/murid/ujian/${muridId}`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    console.log('Ujian murid data:', data); // Debug log
                    const tbody = document.getElementById('ujianTableBody');
                    tbody.innerHTML = '';

                    if (!data || data.length === 0) {
                        tbody.innerHTML = '<tr><td colspan="7" class="text-center text-muted">Belum ada pendaftaran ujian</td></tr>';
                        return;
                    }

                    data.forEach(item => {
                        const tanggalUjian = new Date(item.tanggal_ujian).toLocaleDateString('id-ID');
                        const waktuUjian = `${item.waktu_mulai} - ${item.waktu_selesai}`;
                        const sabukInfo = `${item.sabuk_dari} → ${item.sabuk_ke}`;

                        // Auto-update status logic: if paid, status should be 'diterima'
                        let currentStatus = item.status_pendaftaran;
                        if (item.status_pembayaran === 'sudah_bayar' && currentStatus === 'terdaftar') {
                            currentStatus = 'diterima';
                            // You might want to update this in the backend as well
                        }

                        let statusBadge = '';
                        switch(currentStatus) {
                            case 'terdaftar':
                                statusBadge = '<span class="badge bg-primary text-white">Terdaftar</span>';
                                break;
                            case 'diterima':
                                statusBadge = '<span class="badge bg-success text-white">Diterima</span>';
                                break;
                            case 'ditolak':
                                statusBadge = '<span class="badge bg-danger text-white">Ditolak</span>';
                                break;
                            case 'dibatalkan':
                                statusBadge = '<span class="badge bg-secondary text-white">Dibatalkan</span>';
                                break;
                            default:
                                statusBadge = '<span class="badge bg-dark text-white">Status Tidak Dikenal</span>';
                        }
                    
                        let pembayaranBadge = '';
                        switch(item.status_pembayaran) {
                            case 'belum_bayar':
                                pembayaranBadge = '<span class="badge bg-warning text-dark">Belum Bayar</span>';
                                break;
                            case 'sudah_bayar':
                                pembayaranBadge = '<span class="badge bg-success text-white">Sudah Bayar</span>';
                                break;
                            case 'refund':
                                pembayaranBadge = '<span class="badge bg-info text-dark">Refund</span>';
                                break;
                            default:
                                pembayaranBadge = '<span class="badge bg-light text-dark">Status Tidak Dikenal</span>';
                        }
                    
                        let actionButtons = '';
                        // Updated action button logic based on new status rules
                        if (currentStatus === 'terdaftar') {
                            if (item.status_pembayaran === 'belum_bayar') {
                                actionButtons = `
                                    <button class="btn btn-danger btn-sm me-1" onclick="batalUjian(${item.id})">
                                        <i class="bi bi-x-circle"></i> Batal
                                    </button>
                                    <button class="btn btn-info btn-sm" onclick="bayarUjian(${item.id})">
                                        <i class="bi bi-credit-card"></i> Bayar
                                    </button>
                                `;
                            } else {
                                // If already paid, status should have been 'diterima', but if still 'terdaftar' for some reason
                                actionButtons = `
                                    <button class="btn btn-danger btn-sm" onclick="batalUjian(${item.id})">
                                        <i class="bi bi-x-circle"></i> Batal
                                    </button>
                                `;
                            }
                        } else if (currentStatus === 'diterima') {
                            // For accepted students, maybe only allow cancellation under certain conditions
                            actionButtons = `
                                <span class="text-muted small">
                                    <i class="bi bi-check-circle"></i> Telah Diterima
                                </span>
                            `;
                        }

                        const row = `
                            <tr>
                                <td>${item.nama_ujian || 'N/A'}</td>
                                <td>${tanggalUjian}</td>
                                <td>${waktuUjian}</td>
                                <td>${sabukInfo}</td>
                                <td>${statusBadge}</td>
                                <td>${pembayaranBadge}</td>
                                <td>${actionButtons}</td>
                            </tr>
                        `;
                        tbody.innerHTML += row;
                    });
                })
                .catch(error => {
                    console.error('Error loading ujian:', error);
                    document.getElementById('ujianTableBody').innerHTML = 
                        '<tr><td colspan="7" class="text-center error-message">Error loading data: ' + error.message + '</td></tr>';
                });
        }       

        // Load jadwal yang tersedia untuk didaftarkan
        function loadAvailableJadwal() {
            const selectElement = document.getElementById('selectJadwal');
            selectElement.innerHTML = '<option value="">Memuat jadwal...</option>';

            fetch('/api/jadwal-tersedia')
                .then(response => {
                    console.log('Response status:', response.status);
                    console.log('Response headers:', response.headers.get('content-type'));
                    
                    if (!response.ok) {
                        return response.text().then(text => {
                            console.error('Error response text:', text);
                            throw new Error(`HTTP ${response.status}: ${text}`);
                        });
                    }
                    
                    // Check if response is JSON
                    const contentType = response.headers.get('content-type');
                    if (!contentType || !contentType.includes('application/json')) {
                        return response.text().then(text => {
                            console.error('Non-JSON response:', text);
                            throw new Error('Response bukan JSON format');
                        });
                    }
                    
                    return response.json();
                })
                .then(data => {
                    console.log('Available jadwal data:', data); // Debug log
                    console.log('Data type:', typeof data);
                    console.log('Is array:', Array.isArray(data));
                    
                    selectElement.innerHTML = '<option value="">Pilih jadwal...</option>';
                    
                    // Handle error response from server
                    if (data.error) {
                        throw new Error(data.error);
                    }
                    
                    if (!data || data.length === 0) {
                        selectElement.innerHTML = '<option value="">Tidak ada jadwal tersedia</option>';
                        return;
                    }
                    
                    data.forEach((jadwal, index) => {
                        console.log(`Jadwal ${index}:`, jadwal);
                        
                        const option = document.createElement('option');
                        option.value = jadwal.jadwal_id || jadwal.id || '';
                        
                        // Build text with safe checks
                        const hari = jadwal.hari || 'Unknown';
                        const jam = jadwal.jam || `${jadwal.jam_mulai || 'N/A'} - ${jadwal.jam_selesai || 'N/A'}`;
                        const pelatih = jadwal.pelatih || jadwal.nama_pelatih || 'N/A';
                        const unit = jadwal.unit || jadwal.nama_unit || 'N/A';
                        
                        option.textContent = `${hari} ${jam} - ${pelatih} (${unit})`;
                        selectElement.appendChild(option);
                    });
                })
                .catch(error => {
                    console.error('Error loading available jadwal:', error);
                    selectElement.innerHTML = `<option value="">Error: ${error.message}</option>`;
                    alert('Error memuat jadwal: ' + error.message);
                });
        }

            // Load ujian yang tersedia untuk didaftarkan
            function loadAvailableUjian() {
                const selectElement = document.getElementById('selectUjian');
                selectElement.innerHTML = '<option value="">Memuat ujian...</option>';
            
                fetch('/api/ujian-tersedia')
                    .then(response => {
                        console.log('Response status:', response.status);
                        console.log('Response headers:', response.headers.get('content-type'));

                        if (!response.ok) {
                            return response.text().then(text => {
                                console.error('Error response text:', text);
                                throw new Error(`HTTP ${response.status}: ${text}`);
                            });
                        }

                        // Check if response is JSON
                        const contentType = response.headers.get('content-type');
                        if (!contentType || !contentType.includes('application/json')) {
                            return response.text().then(text => {
                                console.error('Non-JSON response:', text);
                                throw new Error('Response bukan JSON format');
                            });
                        }

                        return response.json();
                    })
                    .then(data => {
                        console.log('Available ujian data:', data); // Debug log
                        console.log('Data type:', typeof data);
                        console.log('Is array:', Array.isArray(data));

                        selectElement.innerHTML = '<option value="">Pilih ujian...</option>';

                        // Handle error response from server
                        if (data.error) {
                            throw new Error(data.error);
                        }

                        if (!data || data.length === 0) {
                            selectElement.innerHTML = '<option value="">Tidak ada ujian tersedia</option>';
                            return;
                        }

                        data.forEach((ujian, index) => {
                            console.log(`Ujian ${index}:`, ujian);

                            const option = document.createElement('option');
                            option.value = ujian.ujian_id || ujian.id || '';

                            // Build text with safe checks
                            const namaUjian = ujian.nama_ujian || 'N/A';
                            const tanggalUjian = ujian.tanggal_ujian ? new Date(ujian.tanggal_ujian).toLocaleDateString('id-ID') : 'N/A';
                            const sabukInfo = `${ujian.sabuk_dari || 'N/A'} → ${ujian.sabuk_ke || 'N/A'}`;
                            const unit = ujian.nama_unit || 'N/A';
                            const biaya = ujian.biaya_ujian ? `Rp ${Number(ujian.biaya_ujian).toLocaleString('id-ID')}` : 'Gratis';

                            option.textContent = `${namaUjian} - ${tanggalUjian} (${sabukInfo}) - ${unit} - ${biaya}`;
                            option.setAttribute('data-ujian', JSON.stringify(ujian));
                            selectElement.appendChild(option);
                        });
                    })
                    .catch(error => {
                        console.error('Error loading available ujian:', error);
                        selectElement.innerHTML = `<option value="">Error: ${error.message}</option>`;
                        alert('Error memuat ujian: ' + error.message);
                    });
            }

            // Add event listener untuk menampilkan detail ujian ketika dipilih
            document.addEventListener('DOMContentLoaded', function() {
                // ... existing code ...
            
                // Add this event listener for ujian selection
                const selectUjian = document.getElementById('selectUjian');
                if (selectUjian) {
                    selectUjian.addEventListener('change', function() {
                        const selectedOption = this.options[this.selectedIndex];
                        const ujianInfo = document.getElementById('ujianInfo');
                        const ujianDetails = document.getElementById('ujianDetails');

                        if (selectedOption.value && selectedOption.getAttribute('data-ujian')) {
                            const ujian = JSON.parse(selectedOption.getAttribute('data-ujian'));
                            const tanggalUjian = ujian.tanggal_ujian ? new Date(ujian.tanggal_ujian).toLocaleDateString('id-ID') : 'N/A';
                            const waktu = `${ujian.waktu_mulai || 'N/A'} - ${ujian.waktu_selesai || 'N/A'}`;
                            const biaya = ujian.biaya_ujian ? `Rp ${Number(ujian.biaya_ujian).toLocaleString('id-ID')}` : 'Gratis';

                            ujianDetails.innerHTML = `
                                <div class="row">
                                    <div class="col-md-6">
                                        <p><strong>Tanggal:</strong> ${tanggalUjian}</p>
                                        <p><strong>Waktu:</strong> ${waktu}</p>
                                        <p><strong>Unit:</strong> ${ujian.nama_unit || 'N/A'}</p>
                                    </div>
                                    <div class="col-md-6">
                                        <p><strong>Pelatih:</strong> ${ujian.nama_pelatih || 'N/A'}</p>
                                        <p><strong>Biaya:</strong> ${biaya}</p>
                                        <p><strong>Kuota:</strong> ${ujian.kuota_peserta || 'N/A'} peserta</p>
                                    </div>
                                </div>
                                ${ujian.persyaratan ? `<p><strong>Persyaratan:</strong> ${ujian.persyaratan}</p>` : ''}
                                ${ujian.keterangan ? `<p><strong>Keterangan:</strong> ${ujian.keterangan}</p>` : ''}
                            `;
                            ujianInfo.style.display = 'block';
                        } else {
                            ujianInfo.style.display = 'none';
                        }
                    });
                }
            });


            function bayarUjian(pendaftaranId) {
                if (!confirm('Konfirmasi pembayaran ujian? Status akan berubah menjadi "Diterima".')) return;

                const csrfToken = document.querySelector('meta[name="csrf-token"]');
                if (!csrfToken) {
                    alert('CSRF token tidak tersedia. Silakan refresh halaman.');
                    return;
                }

                // Prepare the request data
                const requestData = {
                    tanggal_bayar: new Date().toISOString().split('T')[0],
                    status_pendaftaran: 'diterima',
                    status_pembayaran: 'sudah_bayar'
                };

                console.log('Sending payment request:', requestData); // Debug log

                fetch(`/pendaftaran-ujian/bayar/${pendaftaranId}`, {
                    method: 'PUT',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken.getAttribute('content'),
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(requestData)
                })
                .then(response => {
                    console.log('Response status:', response.status);
                    console.log('Response headers:', response.headers);

                    // Handle non-JSON responses
                    const contentType = response.headers.get('content-type');
                    if (!contentType || !contentType.includes('application/json')) {
                        return response.text().then(text => {
                            console.error('Non-JSON response:', text);
                            throw new Error(`Server returned non-JSON response: ${text.substring(0, 100)}...`);
                        });
                    }

                    return response.json().then(data => {
                        if (!response.ok) {
                            throw new Error(data.message || `HTTP error! status: ${response.status}`);
                        }
                        return data;
                    });
                })
                .then(data => {
                    console.log('Payment response:', data);

                    if (data.success) {
                        alert(data.message || 'Pembayaran berhasil dikonfirmasi. Status berubah menjadi "Diterima".');
                        loadUjianMurid(); // Reload data
                    } else {
                        alert('Error: ' + (data.message || 'Gagal mengkonfirmasi pembayaran'));
                    }
                })
                .catch(error => {
                    console.error('Payment error details:', error);

                    // More specific error messages
                    let errorMessage = 'Terjadi kesalahan sistem';
                    if (error.message) {
                        if (error.message.includes('JSON')) {
                            errorMessage = 'Server mengembalikan response yang tidak valid. Silakan coba lagi.';
                        } else if (error.message.includes('Network')) {
                            errorMessage = 'Koneksi internet bermasalah. Periksa koneksi Anda.';
                        } else {
                            errorMessage = error.message;
                        }
                    }

                    alert(errorMessage);
                });
            }

            // Improved simpanUjian function with better error handling
            function simpanUjian() {
                const ujianId = document.getElementById('selectUjian').value;
                const tanggalDaftar = document.getElementById('tanggalDaftarUjian').value;
                const catatan = document.getElementById('catatanPendaftaran').value;
                const muridId = '{{ $murid->murid_id }}';
                const csrfToken = document.querySelector('meta[name="csrf-token"]');

                // Validasi input
                if (!ujianId || !tanggalDaftar) {
                    alert('Mohon lengkapi semua field yang wajib diisi');
                    return;
                }
            
                if (!csrfToken) {
                    alert('CSRF token tidak tersedia. Silakan refresh halaman.');
                    return;
                }
            
                // Show loading
                document.getElementById('loadingMessageUjian').style.display = 'block';
                document.getElementById('btnSimpanUjian').disabled = true;
                document.getElementById('formTambahUjian').style.display = 'none';

                const requestData = {
                    murid_id: muridId,
                    ujian_id: parseInt(ujianId), // Ensure it's an integer
                    tanggal_daftar: tanggalDaftar,
                    catatan_pendaftaran: catatan || null
                };
            
                console.log('Sending ujian registration data:', requestData); // Debug log

                fetch('/pendaftaran-ujian/daftar', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken.getAttribute('content'),
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(requestData)
                })
                .then(response => {
                    console.log('Response status:', response.status);

                    // Check for content type
                    const contentType = response.headers.get('content-type');
                    if (!contentType || !contentType.includes('application/json')) {
                        return response.text().then(text => {
                            console.error('Non-JSON response:', text);
                            throw new Error(`Server returned non-JSON response: ${text.substring(0, 200)}...`);
                        });
                    }

                    return response.json().then(data => {
                        if (!response.ok) {
                            throw new Error(data.message || `HTTP error! status: ${response.status}`);
                        }
                        return data;
                    });
                })
                .then(data => {
                    console.log('Registration response:', data);

                    // Hide loading
                    document.getElementById('loadingMessageUjian').style.display = 'none';
                    document.getElementById('btnSimpanUjian').disabled = false;
                    document.getElementById('formTambahUjian').style.display = 'block';

                    if (data.success) {
                        alert(data.message || 'Pendaftaran ujian berhasil ditambahkan');

                        // Close modal
                        const modal = bootstrap.Modal.getInstance(document.getElementById('tambahUjianModal'));
                        if (modal) {
                            modal.hide();
                        }

                        // Reset form dan reload data
                        document.getElementById('formTambahUjian').reset();
                        document.getElementById('tanggalDaftarUjian').value = '{{ date("Y-m-d") }}';
                        document.getElementById('ujianInfo').style.display = 'none';
                        loadUjianMurid();
                        loadAvailableUjian();
                    } else {
                        alert('Error: ' + (data.message || 'Terjadi kesalahan'));
                    }
                })
                .catch(error => {
                    console.error('Registration error:', error);

                    // Hide loading
                    document.getElementById('loadingMessageUjian').style.display = 'none';
                    document.getElementById('btnSimpanUjian').disabled = false;
                    document.getElementById('formTambahUjian').style.display = 'block';

                    // Better error messages
                    let errorMessage = 'Terjadi kesalahan sistem';
                    if (error.message) {
                        if (error.message.includes('already registered') || error.message.includes('sudah terdaftar')) {
                            errorMessage = 'Anda sudah terdaftar untuk ujian ini.';
                        } else if (error.message.includes('JSON')) {
                            errorMessage = 'Server mengembalikan response yang tidak valid. Silakan refresh halaman dan coba lagi.';
                        } else {
                            errorMessage = error.message;
                        }
                    }

                    alert(errorMessage);
                });
            }

        // Event listener untuk modal reset - Jadwal
        document.getElementById('tambahJadwalModal').addEventListener('hidden.bs.modal', function () {
            document.getElementById('formTambahJadwal').reset();
            document.getElementById('tanggalDaftar').value = '{{ date("Y-m-d") }}';
            document.getElementById('loadingMessage').style.display = 'none';
            document.getElementById('btnSimpan').disabled = false;
            document.getElementById('formTambahJadwal').style.display = 'block';
        });

        // Event listener untuk modal reset - Ujian
        document.getElementById('tambahUjianModal').addEventListener('hidden.bs.modal', function () {
            document.getElementById('formTambahUjian').reset();
            document.getElementById('tanggalDaftarUjian').value = '{{ date("Y-m-d") }}';
            document.getElementById('loadingMessageUjian').style.display = 'none';
            document.getElementById('btnSimpanUjian').disabled = false;
            document.getElementById('formTambahUjian').style.display = 'block';
            document.getElementById('ujianInfo').style.display = 'none';
        });
    </script>
@endsection