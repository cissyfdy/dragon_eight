<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UnitController;
use App\Http\Controllers\MuridController;
use App\Http\Controllers\PelatihController;
use App\Http\Controllers\JadwalController;
use App\Http\Controllers\IuranController;
use App\Http\Controllers\UjianController;
use App\Http\Controllers\PendaftaranUjianController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// welcome page
Route::get('/', function () {
    return view('welcome');
});

// Route login
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

// Protected Routes (require authentication)
Route::middleware('auth')->group(function () {
    // Logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});

// Protected routes dengan middleware
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin/dashboard', [AuthController::class, 'dashboard'])->name('admin.dashboard');
});

Route::middleware(['auth', 'role:murid'])->group(function () {
    Route::get('/murid/dashboard', [AuthController::class, 'muridDashboard'])->name('murid.dashboard');
});

Route::middleware(['auth', 'role:pelatih'])->group(function () {
    Route::get('/pelatih/dashboard', [AuthController::class, 'pelatihDashboard'])->name('pelatih.dashboard');
});

// Murid Routes (Protected - only for murid role)
Route::middleware(['auth', \App\Http\Middleware\RoleMiddleware::class.':murid'])->prefix('murid')->group(function () {
    Route::get('/dashboard', [MuridController::class, 'dashboard'])->name('murid.dashboard');
    Route::get('/profile', [MuridController::class, 'profile'])->name('murid.profile');
    Route::put('/profile/update', [MuridController::class, 'updateProfile'])->name('murid.profile.update');
    Route::get('/jadwal', [MuridController::class, 'jadwal'])->name('murid.jadwal');
    Route::get('/iuran', [MuridController::class, 'iuran'])->name('murid.iuran');
    Route::get('/ujian', [MuridController::class, 'ujian'])->name('murid.ujian');
    
    // Pendaftaran jadwal
    Route::post('/jadwal/daftar', [JadwalController::class, 'daftarMuridJadwal'])->name('murid.jadwal.daftar');
    Route::delete('/jadwal/batal/{id}', [JadwalController::class, 'batalJadwalMurid'])->name('murid.jadwal.batal');
    
    // Pendaftaran ujian
    Route::post('/ujian/daftar', [PendaftaranUjianController::class, 'store'])->name('murid.ujian.daftar');
    Route::delete('/ujian/batal/{id}', [PendaftaranUjianController::class, 'cancel'])->name('murid.ujian.batal');
});

// Admin Routes (Protected - only for admin role)
Route::middleware(['auth', 'role:admin'])->prefix('admin')->group(function () {
    // Dashboard
    Route::get('/dashboard', [AuthController::class, 'dashboard'])->name('admin.dashboard');
    
    // profil klub route
    Route::get('/profilKlub/{id}', [UnitController::class, 'profil'])->name('admin.profilKlub');

    //unit route
    Route::get('/tampilUnit', [UnitController::class, 'tampilUnit'])->name('admin.tampilUnit');
    Route::get('/tambahUnit', [UnitController::class, 'tambahUnit'])->name('admin.tambahUnit');
    Route::post('/unit/delete/{id}',[UnitController::class, 'delete'])->name('unit.delete');
    Route::post('/unit/add', [UnitController::class, 'add'])->name('unit.add');
    Route::get('/unit/edit/{id}', [UnitController::class, 'edit'])->name('admin.editUnit');
    Route::post('/unit/update/{id}',[UnitController::class, 'update'])->name('unit.update');

    //murid route
    Route::get('/tampilMurid', [MuridController::class, 'tampilMurid'])->name('admin.tampilMurid');
    Route::get('/tambahMurid', [MuridController::class, 'tambahMurid'])->name('admin.tambahMurid');
    Route::delete('/murid/delete/{id}', [MuridController::class, 'delete'])->name('murid.delete');
    Route::post('/murid/add', [MuridController::class, 'add'])->name('murid.add');
    Route::get('/murid/edit/{id}', [MuridController::class, 'edit'])->name('admin.editMurid');
    Route::post('/murid/update/{id}',[MuridController::class, 'update'])->name('murid.update');

    // profil Murid route
    Route::get('/profiMurid/{id}', [MuridController::class, 'profil'])->name('admin.profilMurid');

    //Pelatih Route
    Route::get('/tampilPelatih', [PelatihController::class, 'tampilPelatih'])->name('admin.tampilPelatih');
    Route::get('/tambahPelatih', [PelatihController::class, 'tambahPelatih'])->name('admin.tambahPelatih');
    Route::post('/pelatih/delete/{id}',[PelatihController::class, 'delete'])->name('pelatih.delete');
    Route::post('/pelatih/add', [PelatihController::class, 'add'])->name('pelatih.add');
    Route::get('/pelatih/edit/{id}', [PelatihController::class, 'edit'])->name('admin.editPelatih');
    Route::post('/pelatih/update/{id}',[PelatihController::class, 'update'])->name('pelatih.update');

    // profil Pelatih route
    Route::get('/profiPelatih/{id}', [PelatihController::class, 'profil'])->name('admin.profilPelatih');

    // Tampil jadwal
    Route::get('/tampilJadwal', [JadwalController::class, 'tampilJadwal'])->name('admin.tampilJadwal');
     
    // Tambah jadwal
    Route::get('/tambahJadwal', [JadwalController::class, 'tambahJadwal'])->name('admin.tambahJadwal');
    Route::post('/jadwal/store', [JadwalController::class, 'store'])->name('jadwal.store');

    // Edit jadwal
    Route::get('/jadwal/edit/{id}', [JadwalController::class, 'edit'])->name('admin.editJadwal');
    Route::put('/jadwal/update/{id}', [JadwalController::class, 'update'])->name('jadwal.update');

    // Delete jadwal
    Route::delete('/jadwal/delete/{id}', [JadwalController::class, 'destroy'])->name('jadwal.delete');

    // Detail jadwal (untuk modal)
    Route::get('/jadwal/detail/{id}', [JadwalController::class, 'detail'])->name('jadwal.detail');

    // Export jadwal
    Route::get('/jadwal/export', [JadwalController::class, 'export'])->name('jadwal.export');

    //Iuran Route
    Route::get('/tampilIuran', [IuranController::class, 'tampilIuran'])->name('admin.tampilIuran');
    Route::get('/tambahIuran', [IuranController::class, 'tambahIuran'])->name('admin.tambahIuran');
    Route::post('/iuran/add', [IuranController::class, 'store'])->name('iuran.add');
    Route::get('/iuran/edit/{id}', [IuranController::class, 'edit'])->name('admin.editIuran');
    Route::post('/iuran/update/{id}', [IuranController::class, 'update'])->name('iuran.update');
    Route::post('/iuran/delete/{id}', [IuranController::class, 'delete'])->name('iuran.delete');
    Route::post('/iuran/bayar/{id}', [IuranController::class, 'bayar'])->name('iuran.bayar');
    Route::post('/iuran/generate', [IuranController::class, 'generateIuranBulanan'])->name('iuran.generate');
    Route::get('/iuran/export', [IuranController::class, 'export'])->name('iuran.export');

    // Ujian Routes
    Route::get('/tampilUjian', [UjianController::class, 'tampilUjian'])->name('admin.tampilUjian');
    Route::get('/ujian/tambah', [UjianController::class, 'tambahUjian'])->name('admin.tambahUjian');
    Route::post('/ujian/store', [UjianController::class, 'storeUjian'])->name('admin.storeUjian');
    Route::get('/ujian/edit/{id}', [UjianController::class, 'editUjian'])->name('admin.editUjian');
    Route::put('/ujian/update/{id}', [UjianController::class, 'updateUjian'])->name('admin.updateUjian');
    Route::delete('/ujian/delete/{id}', [UjianController::class, 'deleteUjian'])->name('deleteUjian');
    
    // Ajax routes untuk detail ujian
    Route::get('/ujian/detail/{id}', [UjianController::class, 'detailUjian'])->name('detailUjian');
    
    // Export routes
    Route::get('/ujian/export', [UjianController::class, 'exportUjian'])->name('exportUjian');
});

// API Routes - for AJAX calls
Route::middleware(['auth'])->group(function () {
    Route::get('/api/jadwal-tersedia', [JadwalController::class, 'jadwalTersedia'])->name('api.jadwal.tersedia');
    Route::get('/api/ujian-tersedia', [UjianController::class, 'ujianTersedia'])->name('api.ujian.tersedia');
    Route::get('/murid/jadwal/{murid_id}', [MuridController::class, 'jadwalMurid'])->name('api.murid.jadwal.data');
    Route::get('/murid/ujian/{murid_id}', [MuridController::class, 'ujianMurid'])->name('api.murid.ujian.data');
    Route::get('/murid/ujian/{murid_id}', [UjianController::class, 'ujianMurid'])->name('api.murid.ujian.data');
    Route::get('/murid/iuran/{murid_id}', [MuridController::class, 'iuranMurid'])->name('api.murid.iuran.data');
    
    // Registration endpoints
    Route::post('/pendaftaran-jadwal/daftar', [JadwalController::class, 'daftarMuridJadwal'])->name('pendaftaran.jadwal.daftar');
    Route::delete('/pendaftaran-jadwal/batal/{id}', [JadwalController::class, 'batalJadwalMurid'])->name('pendaftaran.jadwal.batal');
    Route::post('/pendaftaran-ujian/daftar', [PendaftaranUjianController::class, 'store'])->name('pendaftaran.ujian.daftar');
    Route::delete('/pendaftaran-ujian/batal/{id}', [PendaftaranUjianController::class, 'cancel'])->name('pendaftaran.ujian.batal');
    Route::put('/pendaftaran-ujian/bayar/{id}', [PendaftaranUjianController::class, 'confirmPayment'])->name('pendaftaran.ujian.bayar');
});