<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pelatih;
use App\Models\User;
use App\Models\Jadwal;
use App\Models\Unit;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class PelatihController extends Controller
{
    public function profil($id)
    {
        // Ambil data pelatih
        $pelatih = Pelatih::findOrFail($id);
        
        // Ambil jadwal mengajar pelatih beserta data unit dan jumlah murid terdaftar
        $jadwal = DB::table('jadwal')
            ->join('unit', 'jadwal.unit_id', '=', 'unit.unit_id')
            ->leftJoin('pendaftaran_jadwal', function($join) {
                $join->on('jadwal.jadwal_id', '=', 'pendaftaran_jadwal.jadwal_id')
                     ->where('pendaftaran_jadwal.status', '=', 'aktif');
            })
            ->where('jadwal.pelatih_id', $id)
            ->where('jadwal.status', 'aktif')
            ->select(
                'jadwal.*',
                'unit.nama_unit',
                'unit.alamat as alamat_unit',
                DB::raw('COUNT(pendaftaran_jadwal.murid_id) as jumlah_murid')
            )
            ->groupBy(
                'jadwal.jadwal_id',
                'jadwal.hari',
                'jadwal.jam_mulai',
                'jadwal.jam_selesai',
                'jadwal.unit_id',
                'jadwal.pelatih_id',
                'jadwal.keterangan',
                'jadwal.status',
                'jadwal.created_at',
                'jadwal.updated_at',
                'unit.nama_unit',
                'unit.alamat'
            )
            ->orderBy('jadwal.hari')
            ->orderBy('jadwal.jam_mulai')
            ->get();

        // Array untuk mengurutkan hari
        $hariUrutan = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'];
        
        // Urutkan jadwal berdasarkan hari
        $jadwal = $jadwal->sortBy(function($item) use ($hariUrutan) {
            return array_search($item->hari, $hariUrutan);
        });
        
        return view('admin.profilPelatih', compact('pelatih', 'jadwal'));
    }
    
    // Method lainnya tetap sama...
    public function tampilPelatih()
    {
        $pelatih = Pelatih::all();
        return view('admin.tampilPelatih', compact('pelatih'));
    }

    public function tambahPelatih()
    {
        return view('admin.tambahPelatih');
    }

    public function add(Request $request)
    {
        $request->validate([
            'nama_pelatih' => 'required|string|max:255',
            'alamat' => 'nullable|string',
            'no_hp' => 'nullable|string|max:20',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6'
        ]);

        // Buat user terlebih dahulu
        $user = User::create([
            'name' => $request->nama_pelatih,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'pelatih'
        ]);

        // Buat data pelatih
        Pelatih::create([
            'nama_pelatih' => $request->nama_pelatih,
            'id' => $user->id,
            'alamat' => $request->alamat,
            'no_hp' => $request->no_hp
        ]);

        return redirect()->route('admin.tampilPelatih')->with('success', 'Pelatih berhasil ditambahkan');
    }

    public function delete($id)
    {
        $pelatih = Pelatih::findOrFail($id);
        
        // Hapus user terkait
        if ($pelatih->id) {
            User::find($pelatih->id)?->delete();
        }
        
        $pelatih->delete();
        
        return redirect()->route('admin.tampilPelatih')->with('success', 'Pelatih berhasil dihapus');
    }

    public function edit($id)
    {
        $pelatih = Pelatih::findOrFail($id);
        $user = User::find($pelatih->id);
        return view('admin.editPelatih', compact('pelatih', 'user'));
    }

    public function update(Request $request, $id)
    {
        $pelatih = Pelatih::findOrFail($id);
    
        $request->validate([
            'nama_pelatih' => 'required|string|max:255',
            'alamat' => 'nullable|string',
            'no_hp' => 'nullable|string|max:20',
        ]);
    
        // Update pelatih data only
        $pelatih->update([
            'nama_pelatih' => $request->nama_pelatih,
            'alamat' => $request->alamat,
            'no_hp' => $request->no_hp
        ]);
    
        // Update user name if user exists
        if ($pelatih->user) {
            $pelatih->user->update([
                'name' => $request->nama_pelatih
            ]);
        }
    
        return redirect()->route('admin.tampilPelatih')->with('success', 'Pelatih berhasil diupdate');
    }
}