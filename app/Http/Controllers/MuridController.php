<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Murid;
use App\Models\Unit;
use App\Models\User;
use App\Models\PendaftaranJadwal;
use App\Models\PendaftaranUjian;
use App\Models\Jadwal;
use App\Models\Iuran;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

use Exception;

class MuridController extends Controller
{

    public function tampilMurid(Request $request)
    {
        $query = Murid::with('unit');
        
        // Filter berdasarkan unit
        if ($request->filled('unit_id')) {
            $query->where('unit_id', $request->unit_id);
        }
        
        // Filter berdasarkan tingkat sabuk
        if ($request->filled('tingkat_sabuk')) {
            $query->where('tingkat_sabuk', $request->tingkat_sabuk);
        }
        
        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama_murid', 'like', "%$search%")
                  ->orWhere('nomor_register', 'like', "%$search%");
            });
        }
        
        $murid = $query->get();
        $units = Unit::all(); // Untuk dropdown filter
        
        return view('admin.tampilMurid', compact('murid', 'units'));
    }

    public function tambahMurid()
    {
        $units = Unit::select('unit_id', 'nama_unit')->get();
        return view('admin.tambahMurid', compact('units'));
    }

    public function profil($id)
    {
        $murid = Murid::findOrFail($id);
        return view('admin.profilMurid', compact('murid'));
    }

    function delete($id)
    {
        $murid = Murid::where('murid_id', $id)->firstOrFail();
        $murid->delete();
        return redirect()->route('admin.tampilMurid');
    }

    function edit($id){
        $murid = Murid::where('murid_id', $id)->first();
        return view('admin.editMurid', compact('murid'));
    }

    public function add(Request $request)
    {
        // Validasi input
        $request->validate([
            'nama_murid' => 'required|string|max:255',
            'nomor_register' => 'required|string|max:50|unique:murid,nomor_register',
            'unit_id' => 'required|exists:unit,unit_id',
            'tanggal_lahir' => 'required|date',
            'alamat' => 'required|string',
            'tingkat_sabuk' => 'nullable|string|max:50',
            'no_hp' => 'nullable|string|max:20',
        ], [
            'nama_murid.required' => 'Nama murid harus diisi',
            'nama_murid.max' => 'Nama murid maksimal 255 karakter',
            'nomor_register.required' => 'Nomor register harus diisi',
            'nomor_register.unique' => 'Nomor register sudah digunakan',
            'nomor_register.max' => 'Nomor register maksimal 50 karakter',
            'unit_id.required' => 'Unit harus dipilih',
            'unit_id.exists' => 'Unit yang dipilih tidak valid',
            'tanggal_lahir.required' => 'Tanggal lahir harus diisi',
            'tanggal_lahir.date' => 'Format tanggal lahir tidak valid',
            'alamat.required' => 'Alamat harus diisi',
            'no_hp.max' => 'Nomor HP maksimal 20 karakter',
            'tingkat_sabuk.max' => 'Tingkat sabuk maksimal 50 karakter',
        ]);

        try {
            // Buat user baru terlebih dahulu
            $email = strtolower(str_replace(' ', '', $request->nama_murid)) . '@dragoneight.com';
            
            // Cek apakah email sudah ada, jika ada tambahkan nomor
            $baseEmail = $email;
            $counter = 1;
            while (User::where('email', $email)->exists()) {
                $email = str_replace('@dragoneight.com', $counter . '@dragoneight.com', $baseEmail);
                $counter++;
            }

            // Buat user
            $user = User::create([
                'name' => $request->nama_murid,
                'email' => $email,
                'password' => Hash::make('password123'), // Password default
                'role' => 'murid'
            ]);

            // Simpan data murid (murid_id akan auto-generate oleh trigger)
            Murid::create([
                'nama_murid' => $request->nama_murid,
                'id' => $user->id, // Foreign key ke users table
                'nomor_register' => $request->nomor_register,
                'unit_id' => $request->unit_id,
                'tanggal_lahir' => $request->tanggal_lahir,
                'alamat' => $request->alamat,
                'tingkat_sabuk' => $request->tingkat_sabuk,
                'no_hp' => $request->no_hp,
            ]);

            return redirect()->route('admin.tampilMurid')
                           ->with('success', 'Murid berhasil ditambahkan! Email: ' . $email . ', Password: password123');
        } catch (Exception $e) {
            // Jika terjadi error, kembalikan ke form dengan data units
            $units = Unit::select('unit_id', 'nama_unit')->get();
            
            return redirect()->back()
                           ->withInput()
                           ->with('units', $units)
                           ->with('error', 'Gagal menambahkan murid: ' . $e->getMessage());
        }
    }

    function update(Request $request, $id)
    {
        $murid = Murid::where('murid_id', $id)->firstOrFail();
        $murid->murid_id = $request->murid_id;
        $murid->nama_murid = $request->nama_murid;
        $murid->nomor_register = $request->nomor_register;
        $murid->tanggal_lahir = $request->tanggal_lahir;
        $murid->alamat = $request->alamat;
        $murid->tingkat_sabuk = $request->tingkat_sabuk;
        $murid->no_hp = $request->no_hp;
        $murid->save();  // Use save() instead of update()
    
        return redirect()->route('admin.tampilMurid');

    }

    public function jadwalMurid($murid_id)
    {
        try {
            $jadwalMurid = PendaftaranJadwal::where('murid_id', $murid_id)
                                           ->where('status', 'aktif')
                                           ->with(['jadwal.unit', 'jadwal.pelatih'])
                                           ->get()
                                           ->map(function($item) {
                                               return [
                                                   'id' => $item->id,
                                                   'jadwal_id' => $item->jadwal->jadwal_id,
                                                   'hari' => $item->jadwal->hari,
                                                   'jam' => date('H:i', strtotime($item->jadwal->jam_mulai)) . ' - ' . date('H:i', strtotime($item->jadwal->jam_selesai)),
                                                   'pelatih' => $item->jadwal->pelatih->nama_pelatih,
                                                   'unit' => $item->jadwal->unit->nama_unit,
                                                   'tanggal_daftar' => $item->tanggal_daftar->format('d/m/Y'),
                                                   'status' => $item->status
                                               ];
                                           })
                                           ->sortBy(function($item) {
                                               $hariUrutan = [
                                                   'Senin' => 1, 'Selasa' => 2, 'Rabu' => 3,
                                                   'Kamis' => 4, 'Jumat' => 5, 'Sabtu' => 6, 'Minggu' => 7
                                               ];
                                               return $hariUrutan[$item['hari']] ?? 8;
                                           });
                                        
            return response()->json($jadwalMurid->values());
                                        
        } catch (Exception $e) {
            return response()->json([
                'error' => 'Gagal mengambil data jadwal: ' . $e->getMessage()
            ], 500);
        }
    }

        /**
     * Display Murid Dashboard
     */
    public function dashboard()
    {
        $user = Auth::user();
        
        // FIXED: Changed from user_id to id
        $murid = Murid::where('id', $user->id)->first();
        
        if (!$murid) {
            return redirect()->route('login')->with('error', 'Data murid tidak ditemukan.');
        }

        // Get dashboard data
        $dashboardData = [
            // Jadwal yang diikuti hari ini
            'jadwalHariIni' => PendaftaranJadwal::with(['jadwal.unit', 'jadwal.pelatih'])
                ->where('murid_id', $murid->murid_id) // Use murid_id for PendaftaranJadwal
                ->whereHas('jadwal', function($query) {
                    $query->whereRaw('DAYOFWEEK(CURDATE()) = CASE 
                        WHEN hari = "Senin" THEN 2
                        WHEN hari = "Selasa" THEN 3  
                        WHEN hari = "Rabu" THEN 4
                        WHEN hari = "Kamis" THEN 5
                        WHEN hari = "Jumat" THEN 6
                        WHEN hari = "Sabtu" THEN 7
                        WHEN hari = "Minggu" THEN 1
                        END');
                })
                ->get(),

            // Iuran yang belum dibayar
            'iuranBelumBayar' => Iuran::where('murid_id', $murid->murid_id) // Use murid_id for Iuran
                ->where('status', 'Belum Lunas') // Fixed column name based on schema
                ->orderBy('tahun', 'desc')
                ->orderBy('bulan', 'desc')
                ->limit(3)
                ->get(),

            // Ujian yang akan datang
            'ujianMendatang' => PendaftaranUjian::with('ujian')
                ->where('murid_id', $murid->murid_id) // Use murid_id for PendaftaranUjian
                ->whereHas('ujian', function($query) {
                    $query->where('tanggal_ujian', '>=', now()); // Fixed column name
                })
                ->orderBy('created_at', 'desc')
                ->limit(3)
                ->get(),

            // Summary counts
            'totalJadwal' => PendaftaranJadwal::where('murid_id', $murid->murid_id)->count(),
            'totalIuranBelumBayar' => Iuran::where('murid_id', $murid->murid_id)
                ->where('status', 'Belum Lunas')
                ->count(),
            'totalUjianTerdaftar' => PendaftaranUjian::where('murid_id', $murid->murid_id)->count(),
        ];

        return view('murid.dashboard', compact('murid', 'dashboardData'));
    }

    /**
     * Show murid profile
     */
    public function profile()
    {
        $user = Auth::user();
        // FIXED: Changed from user_id to id
        $murid = Murid::where('id', $user->id)->first();
        
        return view('murid.profile', compact('murid'));
    }

    /**
     * Show murid schedules
     */
    public function jadwal()
    {
        $user = Auth::user();
        // FIXED: Changed from user_id to id
        $murid = Murid::where('id', $user->id)->first();
        
        $jadwalTerdaftar = PendaftaranJadwal::with(['jadwal.unit', 'jadwal.pelatih'])
            ->where('murid_id', $murid->murid_id) // Use murid_id for PendaftaranJadwal
            ->get();

        $jadwalTersedia = Jadwal::with(['unit', 'pelatih'])
            ->whereNotIn('jadwal_id', $jadwalTerdaftar->pluck('jadwal_id')) // Fixed column name
            ->get();

        return view('murid.jadwal', compact('murid', 'jadwalTerdaftar', 'jadwalTersedia'));
    }

    /**
     * Show murid fees
     */
    public function iuran()
    {
        $user = Auth::user();
        // FIXED: Changed from user_id to id
        $murid = Murid::where('id', $user->id)->first();
        
        $iuranList = Iuran::where('murid_id', $murid->murid_id) // Use murid_id for Iuran
            ->orderBy('tahun', 'desc')
            ->orderBy('bulan', 'desc')
            ->get();

        return view('murid.iuran', compact('murid', 'iuranList'));
    }

    /**
     * Show murid exams
     */
    // In your MuridController or wherever you handle the ujian view
    public function ujian()
    {
        $user = Auth::user();
        $murid = $user->murid;

        if (!$murid) {
            return redirect()->back()->with('error', 'Data murid tidak ditemukan');
        }

        return view('murid.ujian', compact('murid'));
    }

    public function ujianMurid($murid_id)
    {
            $ujianMurid = PendaftaranUjian::where('murid_id', $murid_id)
                                         ->with(['ujian.unit', 'ujian.pelatih'])
                                         ->get()
                                         ->map(function($item) {
                                             return [
                                                 'id' => $item->id,
                                                 'ujian_id' => $item->ujian->ujian_id,
                                                 'nama_ujian' => $item->ujian->nama_ujian,
                                                 'tanggal_ujian' => $item->ujian->tanggal_ujian,
                                                 'waktu_mulai' => date('H:i', strtotime($item->ujian->waktu_mulai)),
                                                 'waktu_selesai' => date('H:i', strtotime($item->ujian->waktu_selesai)),
                                                 'sabuk_dari' => $item->ujian->sabuk_dari,
                                                 'sabuk_ke' => $item->ujian->sabuk_ke,
                                                 'biaya_ujian' => $item->ujian->biaya_ujian,
                                                 'status_pendaftaran' => $item->status_pendaftaran,
                                                 'status_pembayaran' => $item->status_pembayaran,
                                                 'tanggal_daftar' => $item->tanggal_daftar,
                                                 'tanggal_bayar' => $item->tanggal_bayar
                                             ];
                                         })
                                         ->sortBy('tanggal_ujian');
                                     
            return response()->json($ujianMurid->values());
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        $murid = Murid::where('id', $user->id)->first();

        if (!$murid) {
            return redirect()->back()->with('error', 'Data murid tidak ditemukan.');
        }

        // Validate input
        $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'no_telp' => 'nullable|string|max:20',
            'tanggal_lahir' => 'nullable|date',
            'alamat' => 'nullable|string|max:500',
        ], [
            'nama.required' => 'Nama lengkap harus diisi',
            'nama.max' => 'Nama lengkap maksimal 255 karakter',
            'email.required' => 'Email harus diisi',
            'email.email' => 'Format email tidak valid',
            'email.unique' => 'Email sudah digunakan oleh pengguna lain',
            'no_telp.max' => 'Nomor telepon maksimal 20 karakter',
            'tanggal_lahir.date' => 'Format tanggal lahir tidak valid',
            'alamat.max' => 'Alamat maksimal 500 karakter',
        ]);

        try {
            // Update user data
            $user->name = $request->nama;
            $user->email = $request->email;
        

            // Update murid data using the same pattern as your existing code
            $murid->nama_murid = $request->nama;
            $murid->alamat = $request->alamat;
            $murid->tanggal_lahir = $request->tanggal_lahir;

            // Handle phone number - check which field your table uses
            if ($request->filled('no_telp')) {
                // Based on your existing code, it looks like you use 'no_hp'
                $murid->no_hp = $request->no_telp;
            }

            $murid->save(); // Use save() method like in your existing update method

            return redirect()->route('murid.profile')
                ->with('success', 'Profil berhasil diperbarui!');

        } catch (Exception $e) {
            Log::error('Error updating murid profile: ' . $e->getMessage());

            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal memperbarui profil. Silakan coba lagi.');
        }
    }
    public function iuranMurid($muridId)
    {
        try {
            $iuran = DB::table('iuran')
                ->where('murid_id', $muridId)
                ->orderBy('tahun', 'desc')
                ->orderByRaw("FIELD(bulan, 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember')")
                ->get();
                
            return response()->json($iuran->toArray());
        } catch (\Exception $e) {
            Log::error('Error in iuranMurid: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to fetch student fees: ' . $e->getMessage()], 500);
        }
    }

    public function absensi()
    {
        $user = Auth::user();
        $murid = Murid::where('id', $user->id)->first();

        if (!$murid) {
            return redirect()->route('login')->with('error', 'Data murid tidak ditemukan.');
        }

        $absensi = DB::table('jadwal_murid as jm')
            ->join('jadwal as j', 'jm.jadwal_id', '=', 'j.jadwal_id')
            ->join('unit as u', 'j.unit_id', '=', 'u.unit_id')
            ->join('pelatih as p', 'j.pelatih_id', '=', 'p.pelatih_id')
            ->where('jm.murid_id', $murid->murid_id)
            ->select([
                'jm.*',
                'j.hari',
                'j.jam_mulai',
                'j.jam_selesai',
                'u.nama_unit',
                'p.nama_pelatih'
            ])
            ->orderBy('jm.tanggal_latihan', 'desc')
            ->paginate(20);
            
        return view('murid.absensi', compact('murid', 'absensi'));
    }

    public function absensiData($murid_id)
    {
        try {
            $absensi = DB::table('jadwal_murid as jm')
                ->join('jadwal as j', 'jm.jadwal_id', '=', 'j.jadwal_id')
                ->join('unit as u', 'j.unit_id', '=', 'u.unit_id')
                ->join('pelatih as p', 'j.pelatih_id', '=', 'p.pelatih_id')
                ->where('jm.murid_id', $murid_id)
                ->whereNotNull('jm.status_kehadiran') // Only get records with attendance status
                ->select([
                    'jm.id',
                    'jm.jadwal_id',
                    'jm.murid_id',
                    'jm.tanggal_latihan',
                    'jm.status_kehadiran',
                    'jm.catatan',
                    'j.hari',
                    'j.jam_mulai',
                    'j.jam_selesai',
                    'u.nama_unit',
                    'p.nama_pelatih'
                ])
                ->orderBy('jm.tanggal_latihan', 'desc')
                ->get();
                
            return response()->json([
                'success' => true,
                'data' => $absensi
            ]);
            
        } catch (Exception $e) {
            Log::error('Error in absensiData: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => 'Gagal mengambil data absensi: ' . $e->getMessage()
            ], 500);
        }
    }
}