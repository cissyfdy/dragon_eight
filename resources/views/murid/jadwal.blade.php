@extends('layouts.murid')

@section('title', 'Jadwal Latihan Saya')

@section('content')
<style>
    .schedule-card {
        border: none;
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        border-radius: 0.5rem;
        transition: all 0.3s ease;
    }
    .schedule-card:hover {
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
        transform: translateY(-2px);
    }
    .day-badge {
        font-weight: 600;
        padding: 0.5rem 1rem;
        border-radius: 0.25rem;
    }
    .time-badge {
        background-color: #e3f2fd;
        color: #1976d2;
        padding: 0.375rem 0.75rem;
        border-radius: 0.25rem;
        font-weight: 500;
    }
    .coach-info {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
 
    .empty-state {
        text-align: center;
        padding: 3rem 2rem;
        color: #6c757d;
    }
    .empty-state i {
        font-size: 4rem;
        margin-bottom: 1rem;
        opacity: 0.5;
    }
</style>

<!-- Pastikan CSRF token tersedia -->
<meta name="csrf-token" content="{{ csrf_token() }}">

<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <!-- Header Section -->
            <div class="header-section">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <h1 class="mb-2">
                            <i class="bi bi-calendar3 me-3"></i>
                            Jadwal Latihan Saya
                        </h1>
                        <p class="mb-0 opacity-75">Lihat jadwal latihan yang sudah terdaftar</p>
                    </div>
                    <div class="col-md-4 text-end">
                        <div class="d-flex align-items-center justify-content-end">
                            <i class="bi bi-clock me-2" style="font-size: 1.5rem;"></i>
                            <div>
                                <small class="d-block">Hari ini</small>
                                <strong id="currentDate"></strong>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Jadwal Latihan Cards -->
            <div class="row" id="jadwalContainer">
                <div class="col-12">
                    <div class="text-center py-5">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <p class="mt-3 text-muted">Memuat jadwal latihan...</p>
                    </div>
                </div>
            </div>

            <!-- Info Panel -->
            <div class="row mt-4">
                <div class="col-12">
                    <div class="card schedule-card">
                        <div class="card-body">
                            <h5 class="card-title">
                                <i class="bi bi-info-circle me-2 text-info"></i>
                                Informasi Penting
                            </h5>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="d-flex align-items-center mb-2">
                                        <i class="bi bi-clock-fill me-2 text-warning"></i>
                                        <span>Datang 15 menit sebelum latihan dimulai</span>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="d-flex align-items-center mb-2">
                                        <i class="bi bi-person-fill me-2 text-success"></i>
                                        <span>Konfirmasi kehadiran dengan pelatih</span>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="d-flex align-items-center mb-2">
                                        <i class="bi bi-telephone-fill me-2 text-primary"></i>
                                        <span>Hubungi pelatih jika berhalangan hadir</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Set current date
    const currentDate = new Date();
    const options = { 
        weekday: 'long', 
        year: 'numeric', 
        month: 'long', 
        day: 'numeric' 
    };
    document.getElementById('currentDate').textContent = currentDate.toLocaleDateString('id-ID', options);

    // Load jadwal murid
    loadJadwalMurid();
});

function loadJadwalMurid() {
    // Get murid_id from session or pass it from controller
    // You need to pass this from your controller to the view
    const muridId = '{{ $murid->murid_id }}';
    if (!muridId) {
        // Try to get from current user relationship
        fetch('/api/current-murid')
            .then(response => response.json())
            .then(data => {
                if (data.murid_id) {
                    loadJadwalData(data.murid_id);
                } else {
                    showError('ID murid tidak ditemukan. Silakan login ulang atau hubungi administrator.');
                }
            })
            .catch(error => {
                console.error('Error getting murid ID:', error);
                showError('Gagal mengambil data murid. Silakan refresh halaman atau login ulang.');
            });
        return;
    }
    
    loadJadwalData(muridId);
}

function loadJadwalData(muridId) {
    
    fetch(`/murid/jadwal/${muridId}`)
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            console.log('Jadwal murid data:', data);
            displayJadwal(data);
        })
        .catch(error => {
            console.error('Error loading jadwal:', error);
            showError('Gagal memuat jadwal latihan: ' + error.message);
        });
}

function displayJadwal(jadwalData) {
    const container = document.getElementById('jadwalContainer');
    
    if (!jadwalData || jadwalData.length === 0) {
        container.innerHTML = `
            <div class="col-12">
                <div class="empty-state">
                    <i class="bi bi-calendar-x"></i>
                    <h4>Belum Ada Jadwal Latihan</h4>
                    <p class="mb-0">Anda belum terdaftar pada jadwal latihan manapun.</p>
                    <small class="text-muted">Silakan hubungi admin atau pelatih untuk mendaftarkan jadwal latihan.</small>
                </div>
            </div>
        `;
        return;
    }

    // Group schedules by day
    const groupedSchedules = groupSchedulesByDay(jadwalData);
    let html = '';

    // Days order for proper sorting
    const daysOrder = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'];
    
    // Sort grouped schedules by day
    const sortedDays = Object.keys(groupedSchedules).sort((a, b) => {
        return daysOrder.indexOf(a) - daysOrder.indexOf(b);
    });

    sortedDays.forEach(day => {
        const schedules = groupedSchedules[day];
        
        schedules.forEach((schedule, index) => {
            const isToday = isScheduleToday(day);
            const cardClass = isToday ? 'border-primary bg-light' : '';
            const badgeClass = isToday ? 'bg-primary text-white' : 'bg-secondary text-white';

            html += `
                <div class="col-lg-6 col-xl-4 mb-4">
                    <div class="card schedule-card h-100 ${cardClass}">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <span class="day-badge ${badgeClass}">${day}</span>
                                ${isToday ? '<span class="badge bg-warning text-dark">Hari Ini</span>' : ''}
                            </div>
                            
                            <div class="mb-3">
                                <div class="d-flex align-items-center mb-2">
                                    <i class="bi bi-clock me-2 text-primary"></i>
                                    <span class="time-badge">${schedule.jam || 'Waktu tidak tersedia'}</span>
                                </div>
                            </div>

                            <div class="coach-info mb-3">
                                <i class="bi bi-person-badge me-2 text-success"></i>
                                <div>
                                    <strong>${schedule.pelatih || 'Pelatih belum ditentukan'}</strong>
                                    <small class="d-block text-muted">Pelatih</small>
                                </div>
                            </div>

                            ${schedule.lokasi ? `
                                <div class="d-flex align-items-center mb-2">
                                    <i class="bi bi-geo-alt me-2 text-info"></i>
                                    <span>${schedule.lokasi}</span>
                                </div>
                            ` : ''}

                            ${schedule.keterangan ? `
                                <div class="d-flex align-items-start">
                                    <i class="bi bi-info-circle me-2 text-warning"></i>
                                    <small class="text-muted">${schedule.keterangan}</small>
                                </div>
                            ` : ''}
                        </div>
                        
                        <div class="card-footer bg-transparent border-0">
                            <small class="text-muted">
                                <i class="bi bi-calendar-check me-1"></i>
                                Terdaftar sejak: ${formatDate(schedule.tanggal_daftar || new Date())}
                            </small>
                        </div>
                    </div>
                </div>
            `;
        });
    });

    container.innerHTML = html;
}

function groupSchedulesByDay(schedules) {
    const grouped = {};
    
    schedules.forEach(schedule => {
        const day = schedule.hari || 'Tidak Diketahui';
        if (!grouped[day]) {
            grouped[day] = [];
        }
        grouped[day].push(schedule);
    });
    
    return grouped;
}

function isScheduleToday(day) {
    const today = new Date();
    const dayNames = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
    const todayName = dayNames[today.getDay()];
    
    return day === todayName;
}

function formatDate(dateString) {
    const date = new Date(dateString);
    return date.toLocaleDateString('id-ID', {
        day: 'numeric',
        month: 'short',
        year: 'numeric'
    });
}

function showError(message) {
    const container = document.getElementById('jadwalContainer');
    container.innerHTML = `
        <div class="col-12">
            <div class="alert alert-danger text-center">
                <i class="bi bi-exclamation-triangle me-2"></i>
                ${message}
            </div>
        </div>
    `;
}
</script>
@endsection