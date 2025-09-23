<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class IuranController extends Controller
{
    public function tampilIuran(Request $request)
    {
        $query = DB::table('iuran')
            ->join('murid', 'iuran.murid_id', '=', 'murid.murid_id')
            ->join('unit', 'murid.unit_id', '=', 'unit.unit_id')
            ->select(
                'iuran.*',
                'murid.nama_murid',
                'unit.nama_unit'
            );

        // Filter berdasarkan bulan
        if ($request->filled('bulan')) {
            $query->where('iuran.bulan', $request->bulan);
        }

        // Filter berdasarkan tahun
        if ($request->filled('tahun')) {
            $query->where('iuran.tahun', $request->tahun);
        }

        // Filter berdasarkan unit
        if ($request->filled('unit_id')) {
            $query->where('murid.unit_id', $request->unit_id);
        }

        // Filter berdasarkan status
        if ($request->filled('status')) {
            $query->where('iuran.status', $request->status);
        }

        // Search berdasarkan nama murid
        if ($request->filled('search')) {
            $query->where('murid.nama_murid', 'like', '%' . $request->search . '%');
        }

        $iuran = $query->orderBy('iuran.tahun', 'desc')
                      ->orderByRaw("FIELD(iuran.bulan, 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember')")
                      ->get();

        // Data untuk dropdown filter
        $units = DB::table('unit')->get();
        $tahunList = DB::table('iuran')->distinct()->pluck('tahun')->sortDesc();
        
        return view('admin.tampilIuran', compact('iuran', 'units', 'tahunList'));
    }

    public function tambahIuran()
    {
        $murid = DB::table('murid')
            ->join('unit', 'murid.unit_id', '=', 'unit.unit_id')
            ->select('murid.*', 'unit.nama_unit')
            ->get();
            
        return view('admin.tambahIuran', compact('murid'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'murid_id' => 'required',
            'bulan' => 'required',
            'tahun' => 'required|integer',
            'nominal' => 'required|numeric|min:0',
        ]);

        // Cek apakah iuran sudah ada
        $exists = DB::table('iuran')
            ->where('murid_id', $request->murid_id)
            ->where('bulan', $request->bulan)
            ->where('tahun', $request->tahun)
            ->exists();

        if ($exists) {
            return redirect()->back()->withErrors(['error' => 'Iuran untuk bulan dan tahun tersebut sudah ada!']);
        }

        DB::table('iuran')->insert([
            'murid_id' => $request->murid_id,
            'bulan' => $request->bulan,
            'tahun' => $request->tahun,
            'nominal' => $request->nominal,
            'status' => 'Belum Lunas',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('admin.tampilIuran')->with('success', 'Iuran berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $iuran = DB::table('iuran')->where('iuran_id', $id)->first();
        
        if (!$iuran) {
            return redirect()->route('admin.tampilIuran')->withErrors(['error' => 'Iuran tidak ditemukan!']);
        }

        $murid = DB::table('murid')
            ->join('unit', 'murid.unit_id', '=', 'unit.unit_id')
            ->select('murid.*', 'unit.nama_unit')
            ->get();

        return view('admin.editIuran', compact('iuran', 'murid'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'murid_id' => 'required',
            'bulan' => 'required',
            'tahun' => 'required|integer',
            'nominal' => 'required|numeric|min:0',
            'status' => 'required|in:Lunas,Belum Lunas',
        ]);

        // Cek apakah iuran sudah ada (kecuali yang sedang diedit)
        $exists = DB::table('iuran')
            ->where('murid_id', $request->murid_id)
            ->where('bulan', $request->bulan)
            ->where('tahun', $request->tahun)
            ->where('iuran_id', '!=', $id)
            ->exists();

        if ($exists) {
            return redirect()->back()->withErrors(['error' => 'Iuran untuk bulan dan tahun tersebut sudah ada!']);
        }

        $updateData = [
            'murid_id' => $request->murid_id,
            'bulan' => $request->bulan,
            'tahun' => $request->tahun,
            'nominal' => $request->nominal,
            'status' => $request->status,
            'updated_at' => now(),
        ];

        // Jika status diubah menjadi lunas, set tanggal bayar
        if ($request->status == 'Lunas' && $request->filled('tanggal_bayar')) {
            $updateData['tanggal_bayar'] = $request->tanggal_bayar;
        } elseif ($request->status == 'Lunas' && !$request->filled('tanggal_bayar')) {
            $updateData['tanggal_bayar'] = now()->format('Y-m-d');
        } elseif ($request->status == 'Belum Lunas') {
            $updateData['tanggal_bayar'] = null;
        }

        DB::table('iuran')->where('iuran_id', $id)->update($updateData);

        return redirect()->route('admin.tampilIuran')->with('success', 'Iuran berhasil diperbarui!');
    }

    public function delete($id)
    {
        $deleted = DB::table('iuran')->where('iuran_id', $id)->delete();

        if ($deleted) {
            return redirect()->route('admin.tampilIuran')->with('success', 'Iuran berhasil dihapus!');
        } else {
            return redirect()->route('admin.tampilIuran')->withErrors(['error' => 'Iuran tidak ditemukan!']);
        }
    }

    public function bayar(Request $request, $id)
    {
        $request->validate([
            'tanggal_bayar' => 'required|date',
        ]);

        DB::table('iuran')
            ->where('iuran_id', $id)
            ->update([
                'status' => 'Lunas',
                'tanggal_bayar' => $request->tanggal_bayar,
                'updated_at' => now(),
            ]);

        return redirect()->route('admin.tampilIuran')->with('success', 'Pembayaran iuran berhasil dicatat!');
    }

    public function generateIuranBulanan(Request $request)
    {
        $request->validate([
            'bulan' => 'required',
            'tahun' => 'required|integer',
        ]);

        $muridList = DB::table('murid')->get();
        $inserted = 0;

        foreach ($muridList as $murid) {
            // Cek apakah iuran sudah ada
            $exists = DB::table('iuran')
                ->where('murid_id', $murid->murid_id)
                ->where('bulan', $request->bulan)
                ->where('tahun', $request->tahun)
                ->exists();

            if (!$exists) {
                DB::table('iuran')->insert([
                    'murid_id' => $murid->murid_id,
                    'bulan' => $request->bulan,
                    'tahun' => $request->tahun,
                    'nominal' => 150000, // default nominal
                    'status' => 'Belum Lunas',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                $inserted++;
            }
        }

        return redirect()->route('admin.tampilIuran')
            ->with('success', "Berhasil generate {$inserted} tagihan iuran untuk {$request->bulan} {$request->tahun}");
    }

    public function export(Request $request)
    {
        $query = DB::table('iuran')
            ->join('murid', 'iuran.murid_id', '=', 'murid.murid_id')
            ->join('unit', 'murid.unit_id', '=', 'unit.unit_id')
            ->select(
                'murid.nama_murid',
                'unit.nama_unit',
                'iuran.bulan',
                'iuran.tahun',
                'iuran.nominal',
                'iuran.tanggal_bayar',
                'iuran.status'
            );

        // Apply same filters as tampilIuran
        if ($request->filled('bulan')) {
            $query->where('iuran.bulan', $request->bulan);
        }
        if ($request->filled('tahun')) {
            $query->where('iuran.tahun', $request->tahun);
        }
        if ($request->filled('unit_id')) {
            $query->where('murid.unit_id', $request->unit_id);
        }
        if ($request->filled('status')) {
            $query->where('iuran.status', $request->status);
        }

        $data = $query->get();

        $filename = 'laporan_iuran_' . date('Y-m-d') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($data) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Nama Murid', 'Unit', 'Bulan', 'Tahun', 'Nominal', 'Tanggal Bayar', 'Status']);

            foreach ($data as $row) {
                fputcsv($file, [
                    $row->nama_murid,
                    $row->nama_unit,
                    $row->bulan,
                    $row->tahun,
                    number_format($row->nominal, 0, ',', '.'),
                    $row->tanggal_bayar ?? '-',
                    $row->status
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}