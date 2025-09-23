<?php

namespace App\Http\Controllers;
use App\Models\Unit;
use App\Models\Murid;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UnitController extends Controller
{
    public function tampilUnit()
    {
        $units = Unit::all();
        return view('admin.tampilUnit',compact('units'));
    }

    public function tambahUnit()
    {
        return view('admin.tambahUnit');
    }

    public function profil($id)
    {
        // Ambil data unit
        $unit = Unit::findOrFail($id);
        
        // Ambil data murid berdasarkan unit
        $murid = Murid::where('unit_id', $id)->get();
        
        // Ambil jadwal berdasarkan unit beserta data pelatih dan jumlah murid terdaftar
        $jadwal = DB::table('jadwal')
            ->join('pelatih', 'jadwal.pelatih_id', '=', 'pelatih.pelatih_id')
            ->leftJoin('pendaftaran_jadwal', function($join) {
                $join->on('jadwal.jadwal_id', '=', 'pendaftaran_jadwal.jadwal_id')
                     ->where('pendaftaran_jadwal.status', '=', 'aktif');
            })
            ->where('jadwal.unit_id', $id)
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
            ->orderBy('jadwal.hari')
            ->orderBy('jadwal.jam_mulai')
            ->get();
        
        return view('admin.profilKlub', compact('unit', 'murid', 'jadwal'));
    }

    function delete($id)
    {
        $unit = Unit::where('unit_id', $id)->firstOrFail();
        $unit->delete();
        return redirect()->route('admin.tampilUnit');
    }

    public function add(Request $request)
    {
        // Validasi input
        $request->validate([
            'no' => 'required|numeric|unique:unit,unit_id',
            'nama_unit' => 'required|string|max:255',
            'alamat' => 'required|string',
        ], [
            'no.required' => 'Nomor harus diisi',
            'no.unique' => 'Nomor sudah digunakan',
            'nama_unit.required' => 'Nama unit harus diisi',
            'alamat.required' => 'Alamat harus diisi',
        ]);

        try {
            // Simpan data ke database
            Unit::create([
                'unit_id' => $request->no,
                'nama_unit' => $request->nama_unit,
                'alamat' => $request->alamat,
            ]);

            return redirect()->route('admin.tampilUnit')
                           ->with('success', 'Unit berhasil ditambahkan!');
        } catch (\Exception $e) {
            return redirect()->back()
                           ->withInput()
                           ->with('error', 'Gagal menambahkan unit: ' . $e->getMessage());
        }
    }

    function edit($id){
        $unit = Unit::where('unit_id', $id)->first();
        return view('admin.editUnit', compact('unit'));
    }

    function update(Request $request, $id)
    {
        $unit = Unit::where('unit_id', $id)->firstOrFail();
        $unit->unit_id = $request->unit_id;
        $unit->nama_unit = $request->nama_unit;
        $unit->alamat = $request->alamat;
        $unit->save();  // Use save() instead of update()
    
        return redirect()->route('admin.tampilUnit');
    }
}
