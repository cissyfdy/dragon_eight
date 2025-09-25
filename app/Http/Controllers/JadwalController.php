<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Jadwal;
use App\Models\Unit;
use App\Models\Pelatih;
use App\Models\PendaftaranJadwal;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class JadwalController extends Controller
{
    public function tampilJadwal(Request $request)
    {
        // Query dasar untuk jadwal dengan join ke tabel terkait
        $query = DB::table('jadwal')
            ->join('unit', 'jadwal.unit_id', '=', 'unit.unit_id')
            ->join('pelatih', 'jadwal.pelatih_id', '=', 'pelatih.pelatih_id')
            ->leftJoin('pendaftaran_jadwal', function($join) {
                $join->on('jadwal.jadwal_id', '=', 'pendaftaran_jadwal.jadwal_id')
                     ->where('pendaftaran_jadwal.status', '=', 'aktif');
            })
            ->select(
                'jadwal.*',
                'unit.nama_unit',
                'unit.alamat as alamat_unit',
                'pelatih.nama_pelatih',
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
                'unit.alamat',
                'pelatih.nama_pelatih'
            );

        // Filter berdasarkan hari
        if ($request->filled('hari')) {
            $query->where('jadwal.hari', $request->hari);
        }

        // Filter berdasarkan unit
        if ($request->filled('unit_id')) {
            $query->where('jadwal.unit_id', $request->unit_id);
        }

        // Filter berdasarkan status
        if ($request->filled('status')) {
            $query->where('jadwal.status', $request->status);
        }

        // Urutkan berdasarkan hari dan jam
        $hariUrutan = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'];
        $query->orderByRaw("FIELD(jadwal.hari, '" . implode("','", $hariUrutan) . "')")
              ->orderBy('jadwal.jam_mulai');

        $jadwal = $query->get();

        // Ambil semua unit untuk filter
        $units = Unit::all();

        return view('admin.tampilJadwal', compact('jadwal', 'units'));
    }

    public function tambahJadwal()
    {
        $units = Unit::all();
        $pelatih = Pelatih::all();
        return view('admin.tambahJadwal', compact('units', 'pelatih'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'hari' => 'required|in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu,Minggu',
            'jam_mulai' => 'required|date_format:H:i',
            'jam_selesai' => 'required|date_format:H:i|after:jam_mulai',
            'unit_id' => 'required|exists:unit,unit_id',
            'pelatih_id' => 'required|exists:pelatih,pelatih_id',
            'keterangan' => 'nullable|string',
            'status' => 'required|in:aktif,tidak_aktif'
        ]);

        // Cek konflik jadwal pelatih
        $konflikPelatih = Jadwal::where('pelatih_id', $request->pelatih_id)
            ->where('hari', $request->hari)
            ->where('status', 'aktif')
            ->where(function($query) use ($request) {
                $query->whereBetween('jam_mulai', [$request->jam_mulai, $request->jam_selesai])
                      ->orWhereBetween('jam_selesai', [$request->jam_mulai, $request->jam_selesai])
                      ->orWhere(function($q) use ($request) {
                          $q->where('jam_mulai', '<=', $request->jam_mulai)
                            ->where('jam_selesai', '>=', $request->jam_selesai);
                      });
            })->exists();

        if ($konflikPelatih) {
            return back()->withErrors(['error' => 'Pelatih sudah memiliki jadwal pada waktu tersebut!'])
                        ->withInput();
        }

        Jadwal::create([
            'hari' => $request->hari,
            'jam_mulai' => $request->jam_mulai,
            'jam_selesai' => $request->jam_selesai,
            'unit_id' => $request->unit_id,
            'pelatih_id' => $request->pelatih_id,
            'keterangan' => $request->keterangan,
            'status' => $request->status
        ]);

        return redirect()->route('admin.tampilJadwal')->with('success', 'Jadwal berhasil ditambahkan');
    }

    public function edit($id)
    {
        $jadwal = Jadwal::findOrFail($id);
        $units = Unit::all();
        $pelatih = Pelatih::all();
        
        return view('admin.editJadwal', compact('jadwal', 'units', 'pelatih'));
    }

    public function update(Request $request, $id)
    {
        $jadwal = Jadwal::findOrFail($id);
        
        $request->validate([
            'hari' => 'required|in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu,Minggu',
            'jam_mulai' => 'required|date_format:H:i',
            'jam_selesai' => 'required|date_format:H:i|after:jam_mulai',
            'unit_id' => 'required|exists:unit,unit_id',
            'pelatih_id' => 'required|exists:pelatih,pelatih_id',
            'keterangan' => 'nullable|string',
            'status' => 'required|in:aktif,tidak_aktif'
        ]);

        // Cek konflik jadwal pelatih (kecuali jadwal yang sedang diedit)
        $konflikPelatih = Jadwal::where('pelatih_id', $request->pelatih_id)
            ->where('hari', $request->hari)
            ->where('status', 'aktif')
            ->where('jadwal_id', '!=', $id)
            ->where(function($query) use ($request) {
                $query->whereBetween('jam_mulai', [$request->jam_mulai, $request->jam_selesai])
                      ->orWhereBetween('jam_selesai', [$request->jam_mulai, $request->jam_selesai])
                      ->orWhere(function($q) use ($request) {
                          $q->where('jam_mulai', '<=', $request->jam_mulai)
                            ->where('jam_selesai', '>=', $request->jam_selesai);
                      });
            })->exists();

        if ($konflikPelatih) {
            return back()->withErrors(['error' => 'Pelatih sudah memiliki jadwal pada waktu tersebut!'])
                        ->withInput();
        }

        $jadwal->update([
            'hari' => $request->hari,
            'jam_mulai' => $request->jam_mulai,
            'jam_selesai' => $request->jam_selesai,
            'unit_id' => $request->unit_id,
            'pelatih_id' => $request->pelatih_id,
            'keterangan' => $request->keterangan,
            'status' => $request->status
        ]);

        return redirect()->route('admin.tampilJadwal')->with('success', 'Jadwal berhasil diupdate');
    }

    public function destroy($id)
    {
        $jadwal = Jadwal::findOrFail($id);
        
        // Cek apakah ada murid yang terdaftar
        $jumlahMurid = PendaftaranJadwal::where('jadwal_id', $id)
                                      ->where('status', 'aktif')
                                      ->count();
        
        if ($jumlahMurid > 0) {
            return back()->withErrors(['error' => 'Tidak dapat menghapus jadwal karena masih ada murid yang terdaftar!']);
        }

        $jadwal->delete();
        
        return redirect()->route('admin.tampilJadwal')->with('success', 'Jadwal berhasil dihapus');
    }

    public function detail($id)
    {
        $jadwal = DB::table('jadwal')
            ->join('unit', 'jadwal.unit_id', '=', 'unit.unit_id')
            ->join('pelatih', 'jadwal.pelatih_id', '=', 'pelatih.pelatih_id')
            ->leftJoin('pendaftaran_jadwal', function($join) {
                $join->on('jadwal.jadwal_id', '=', 'pendaftaran_jadwal.jadwal_id')
                     ->where('pendaftaran_jadwal.status', '=', 'aktif');
            })
            ->where('jadwal.jadwal_id', $id)
            ->select(
                'jadwal.*',
                'unit.nama_unit',
                'unit.alamat as alamat_unit',
                'pelatih.nama_pelatih',
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
                'unit.alamat',
                'pelatih.nama_pelatih'
            )
            ->first();

        return response()->json($jadwal);
    }

    public function jadwalTersedia()
    {
        $jadwal = DB::table('jadwal')
            ->join('unit', 'jadwal.unit_id', '=', 'unit.unit_id')
            ->join('pelatih', 'jadwal.pelatih_id', '=', 'pelatih.pelatih_id')
            ->leftJoin('pendaftaran_jadwal', function($join) {
                $join->on('jadwal.jadwal_id', '=', 'pendaftaran_jadwal.jadwal_id')
                     ->where('pendaftaran_jadwal.status', '=', 'aktif');
            })
            ->where('jadwal.status', 'aktif')
            ->select(
                'jadwal.*',
                'unit.nama_unit',
                'pelatih.nama_pelatih',
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
                'pelatih.nama_pelatih'
            )
            ->orderByRaw("FIELD(jadwal.hari, 'Senin','Selasa','Rabu','Kamis','Jumat','Sabtu','Minggu')")
            ->orderBy('jadwal.jam_mulai')
            ->get();

        return response()->json($jadwal);
    }

    public function daftarMuridJadwal(Request $request)
    {
        $request->validate([
            'murid_id' => 'required|exists:murid,murid_id',
            'jadwal_id' => 'required|exists:jadwal,jadwal_id'
        ]);

        // Cek apakah murid sudah terdaftar di jadwal ini
        $sudahTerdaftar = PendaftaranJadwal::where('murid_id', $request->murid_id)
                                         ->where('jadwal_id', $request->jadwal_id)
                                         ->where('status', 'aktif')
                                         ->exists();

        if ($sudahTerdaftar) {
            return response()->json(['error' => 'Murid sudah terdaftar di jadwal ini'], 400);
        }

        // Cek konflik jadwal murid (apakah murid sudah ada jadwal di hari dan jam yang sama)
        $jadwalBaru = Jadwal::find($request->jadwal_id);
        $konflikJadwal = DB::table('pendaftaran_jadwal')
            ->join('jadwal', 'pendaftaran_jadwal.jadwal_id', '=', 'jadwal.jadwal_id')
            ->where('pendaftaran_jadwal.murid_id', $request->murid_id)
            ->where('pendaftaran_jadwal.status', 'aktif')
            ->where('jadwal.hari', $jadwalBaru->hari)
            ->where('jadwal.status', 'aktif')
            ->where(function($query) use ($jadwalBaru) {
                $query->whereBetween('jadwal.jam_mulai', [$jadwalBaru->jam_mulai, $jadwalBaru->jam_selesai])
                      ->orWhereBetween('jadwal.jam_selesai', [$jadwalBaru->jam_mulai, $jadwalBaru->jam_selesai])
                      ->orWhere(function($q) use ($jadwalBaru) {
                          $q->where('jadwal.jam_mulai', '<=', $jadwalBaru->jam_mulai)
                            ->where('jadwal.jam_selesai', '>=', $jadwalBaru->jam_selesai);
                      });
            })->exists();

        if ($konflikJadwal) {
            return response()->json(['error' => 'Murid sudah memiliki jadwal pada waktu tersebut'], 400);
        }

        PendaftaranJadwal::create([
            'murid_id' => $request->murid_id,
            'jadwal_id' => $request->jadwal_id,
            'tanggal_daftar' => now()->toDateString(),
            'status' => 'aktif'
        ]);

        return response()->json(['success' => 'Murid berhasil didaftarkan ke jadwal']);
    }

    public function batalJadwalMurid($id)
    {
        $pendaftaran = PendaftaranJadwal::findOrFail($id);
        $pendaftaran->update(['status' => 'tidak_aktif']);

        return response()->json(['success' => 'Pendaftaran jadwal berhasil dibatalkan']);
    }

    public function export()
    {
        $jadwal = DB::table('jadwal')
            ->join('unit', 'jadwal.unit_id', '=', 'unit.unit_id')
            ->join('pelatih', 'jadwal.pelatih_id', '=', 'pelatih.pelatih_id')
            ->leftJoin('pendaftaran_jadwal', function($join) {
                $join->on('jadwal.jadwal_id', '=', 'pendaftaran_jadwal.jadwal_id')
                     ->where('pendaftaran_jadwal.status', '=', 'aktif');
            })
            ->select(
                'jadwal.*',
                'unit.nama_unit',
                'unit.alamat as alamat_unit',
                'pelatih.nama_pelatih',
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
                'unit.alamat',
                'pelatih.nama_pelatih'
            )
            ->orderByRaw("FIELD(jadwal.hari, 'Senin','Selasa','Rabu','Kamis','Jumat','Sabtu','Minggu')")
            ->orderBy('jadwal.jam_mulai')
            ->get();

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="jadwal_latihan_' . date('Y-m-d') . '.csv"',
        ];

        $callback = function() use ($jadwal) {
            $file = fopen('php://output', 'w');
            
            // Header CSV
            fputcsv($file, ['Hari', 'Jam Mulai', 'Jam Selesai', 'Unit', 'Alamat Unit', 'Pelatih', 'Jumlah Murid', 'Keterangan', 'Status']);
            
            // Data CSV
            foreach ($jadwal as $j) {
                fputcsv($file, [
                    $j->hari,
                    $j->jam_mulai,
                    $j->jam_selesai,
                    $j->nama_unit,
                    $j->alamat_unit,
                    $j->nama_pelatih,
                    $j->jumlah_murid,
                    $j->keterangan,
                    $j->status
                ]);
            }
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function getJadwalByUnit($unitId)
    {
        $jadwal = DB::table('jadwal')
            ->join('pelatih', 'jadwal.pelatih_id', '=', 'pelatih.pelatih_id')
            ->leftJoin('pendaftaran_jadwal', function($join) {
                $join->on('jadwal.jadwal_id', '=', 'pendaftaran_jadwal.jadwal_id')
                     ->where('pendaftaran_jadwal.status', '=', 'aktif');
            })
            ->where('jadwal.unit_id', $unitId)
            ->where('jadwal.status', 'aktif')
            ->select(
                'jadwal.*',
                'pelatih.nama_pelatih',
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
                'pelatih.nama_pelatih'
            )
            ->orderByRaw("FIELD(jadwal.hari, 'Senin','Selasa','Rabu','Kamis','Jumat','Sabtu','Minggu')")
            ->orderBy('jadwal.jam_mulai')
            ->get();

        return response()->json($jadwal);
    }

    public function getJadwalByPelatih($pelatihId)
    {
        $jadwal = DB::table('jadwal')
            ->join('unit', 'jadwal.unit_id', '=', 'unit.unit_id')
            ->leftJoin('pendaftaran_jadwal', function($join) {
                $join->on('jadwal.jadwal_id', '=', 'pendaftaran_jadwal.jadwal_id')
                     ->where('pendaftaran_jadwal.status', '=', 'aktif');
            })
            ->where('jadwal.pelatih_id', $pelatihId)
            ->where('jadwal.status', 'aktif')
            ->select(
                'jadwal.*',
                'unit.nama_unit',
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
                'unit.nama_unit'
            )
            ->orderByRaw("FIELD(jadwal.hari, 'Senin','Selasa','Rabu','Kamis','Jumat','Sabtu','Minggu')")
            ->orderBy('jadwal.jam_mulai')
            ->get();

        return response()->json($jadwal);
    }

    public function jadwal()
{
    $murid = User::user()->murid; // or however you get the murid relation
    return view('jadwal', compact('murid'));
}
}