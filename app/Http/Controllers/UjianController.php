<?php

namespace App\Http\Controllers;

use App\Models\Ujian;
use App\Models\Unit;
use App\Models\Pelatih;
use App\Models\Murid;
use App\Models\PendaftaranUjian;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class UjianController extends Controller
{
    public function tampilUjian(Request $request)
    {
        $query = Ujian::with(['unit', 'pelatih'])
            ->withCount(['pendaftaranUjian as jumlah_peserta' => function ($query) {
                $query->where('status_pendaftaran', 'diterima');
            }]);

        // Filter berdasarkan status
        if ($request->filled('status')) {
            $query->byStatus($request->status);
        }

        // Filter berdasarkan unit
        if ($request->filled('unit_id')) {
            $query->byUnit($request->unit_id);
        }

        // Filter berdasarkan tanggal
        if ($request->filled('tanggal_dari') && $request->filled('tanggal_sampai')) {
            $query->whereBetween('tanggal_ujian', [
                $request->tanggal_dari,
                $request->tanggal_sampai
            ]);
        }

        // Filter berdasarkan sabuk
        if ($request->filled('sabuk_dari')) {
            $query->where('sabuk_dari', $request->sabuk_dari);
        }

        $ujian = $query->orderBy('tanggal_ujian', 'desc')->get();
        $units = Unit::all();
        $pelatih = Pelatih::all();

        // Data untuk statistik
        $statistics = [
            'total_ujian' => $ujian->count(),
            'ujian_aktif' => $ujian->where('status_ujian', 'dijadwalkan')->count(),
            'ujian_selesai' => $ujian->where('status_ujian', 'selesai')->count(),
            'total_peserta' => $ujian->sum('jumlah_peserta')
        ];

        return view('admin.tampilUjian', compact('ujian', 'units', 'pelatih', 'statistics'));
    }

    public function detailUjian($id)
    {
        $ujian = Ujian::with([
            'unit', 
            'pelatih', 
            'pendaftaranUjian.murid',
            'hasilUjian.murid'
        ])->findOrFail($id);

        return response()->json([
            'ujian_id' => $ujian->ujian_id,
            'nama_ujian' => $ujian->nama_ujian,
            'tanggal_ujian' => $ujian->tanggal_ujian->format('d/m/Y'),
            'waktu_mulai' => $ujian->waktu_mulai->format('H:i'),
            'waktu_selesai' => $ujian->waktu_selesai->format('H:i'),
            'nama_unit' => $ujian->unit->nama_unit,
            'alamat_unit' => $ujian->unit->alamat,
            'nama_pelatih' => $ujian->pelatih->nama_pelatih,
            'sabuk_dari' => $ujian->sabuk_dari,
            'sabuk_ke' => $ujian->sabuk_ke,
            'biaya_ujian' => number_format($ujian->biaya_ujian, 0, ',', '.'),
            'kuota_peserta' => $ujian->kuota_peserta,
            'jumlah_peserta' => $ujian->jumlah_peserta,
            'sisa_kuota' => $ujian->sisa_kuota,
            'status_ujian' => $ujian->status_ujian,
            'persyaratan' => $ujian->persyaratan,
            'keterangan' => $ujian->keterangan,
            'peserta' => $ujian->pendaftaranUjian->map(function ($pendaftaran) {
                return [
                    'nama_murid' => $pendaftaran->murid->nama_murid,
                    'status_pendaftaran' => $pendaftaran->status_pendaftaran,
                    'status_pembayaran' => $pendaftaran->status_pembayaran
                ];
            })
        ]);
    }

    public function tambahUjian()
    {
        $units = Unit::all();
        $pelatih = Pelatih::all();
        return view('admin.tambahUjian', compact('units', 'pelatih'));
    }

    public function storeUjian(Request $request)
    {
        $validatedData = $request->validate([
            'nama_ujian' => 'required|string|max:255',
            'tanggal_ujian' => 'required|date|after:today',
            'waktu_mulai' => 'required',
            'waktu_selesai' => 'required|after:waktu_mulai',
            'unit_id' => 'required|exists:unit,unit_id',
            'pelatih_id' => 'required|exists:pelatih,pelatih_id',
            'sabuk_dari' => 'required|string|max:50',
            'sabuk_ke' => 'required|string|max:50',
            'biaya_ujian' => 'required|numeric|min:0',
            'kuota_peserta' => 'required|integer|min:1',
            'persyaratan' => 'nullable|string',
            'keterangan' => 'nullable|string'
        ]);

        Ujian::create($validatedData);

        return redirect()->route('admin.tampilUjian')
            ->with('success', 'Ujian berhasil ditambahkan!');
    }

    public function editUjian($id)
    {
        $ujian = Ujian::findOrFail($id);
        $units = Unit::all();
        $pelatih = Pelatih::all();
        return view('admin.editUjian', compact('ujian', 'units', 'pelatih'));
    }

    public function updateUjian(Request $request, $id)
    {
        $ujian = Ujian::findOrFail($id);
        
        $validatedData = $request->validate([
            'nama_ujian' => 'required|string|max:255',
            'tanggal_ujian' => 'required|date',
            'waktu_mulai' => 'required',
            'waktu_selesai' => 'required|after:waktu_mulai',
            'unit_id' => 'required|exists:unit,unit_id',
            'pelatih_id' => 'required|exists:pelatih,pelatih_id',
            'sabuk_dari' => 'required|string|max:50',
            'sabuk_ke' => 'required|string|max:50',
            'biaya_ujian' => 'required|numeric|min:0',
            'kuota_peserta' => 'required|integer|min:1',
            'status_ujian' => 'required|in:dijadwalkan,berlangsung,selesai,dibatalkan',
            'persyaratan' => 'nullable|string',
            'keterangan' => 'nullable|string'
        ]);

        $ujian->update($validatedData);

        return redirect()->route('admin.tampilUjian')
            ->with('success', 'Ujian berhasil diupdate!');
    }

    public function deleteUjian($id)
    {
        $ujian = Ujian::findOrFail($id);
        
        // Cek apakah ada peserta yang sudah terdaftar
        if ($ujian->pendaftaranUjian()->count() > 0) {
            return redirect()->route('admin.tampilUjian')
                ->with('error', 'Tidak dapat menghapus ujian yang sudah memiliki peserta!');
        }

        $ujian->delete();

        return redirect()->route('admin.tampilUjian')
            ->with('success', 'Ujian berhasil dihapus!');
    }

    public function exportUjian()
    {
        // Implementasi export data ujian (bisa ke Excel/PDF)
        $ujian = Ujian::with(['unit', 'pelatih'])->get();
        
        // Return download file atau redirect ke halaman export
        return response()->json(['message' => 'Export functionality will be implemented']);
    }

    // THIS IS THE MISSING METHOD - matches the route /api/ujian-tersedia
    public function ujianTersedia(Request $request)
    {
        try {
            Log::info('ujianTersedia method called');
            
            $currentDate = now();
            
            $ujian = DB::table('ujian as u')
                ->leftJoin('unit as unit', 'u.unit_id', '=', 'unit.unit_id')
                ->leftJoin('pelatih as p', 'u.pelatih_id', '=', 'p.pelatih_id')
                ->select(
                    'u.ujian_id',
                    'u.nama_ujian',
                    'u.tanggal_ujian',
                    'u.waktu_mulai',
                    'u.waktu_selesai',
                    'u.sabuk_dari',
                    'u.sabuk_ke',
                    'u.biaya_ujian',
                    'u.kuota_peserta',
                    'u.persyaratan',
                    'u.keterangan',
                    'unit.nama_unit',
                    'p.nama_pelatih',
                    'u.status_ujian'
                )
                ->where('u.tanggal_ujian', '>=', $currentDate->toDateString())
                ->whereIn('u.status_ujian', ['dijadwalkan', 'aktif']) // Allow both status
                ->orderBy('u.tanggal_ujian', 'asc')
                ->get();

            Log::info('Ujian data retrieved', ['count' => $ujian->count(), 'data' => $ujian->toArray()]);

            // Convert to array and ensure proper formatting
            $ujianArray = $ujian->map(function($item) {
                return [
                    'ujian_id' => (int)$item->ujian_id,
                    'id' => (int)$item->ujian_id, // Backup ID field
                    'nama_ujian' => $item->nama_ujian,
                    'tanggal_ujian' => $item->tanggal_ujian,
                    'waktu_mulai' => $item->waktu_mulai,
                    'waktu_selesai' => $item->waktu_selesai,
                    'sabuk_dari' => $item->sabuk_dari,
                    'sabuk_ke' => $item->sabuk_ke,
                    'biaya_ujian' => (float)($item->biaya_ujian ?: 0),
                    'kuota_peserta' => (int)($item->kuota_peserta ?: 0),
                    'persyaratan' => $item->persyaratan,
                    'keterangan' => $item->keterangan,
                    'nama_unit' => $item->nama_unit,
                    'nama_pelatih' => $item->nama_pelatih,
                    'status_ujian' => $item->status_ujian
                ];
            })->toArray();

            Log::info('Returning ujian data', ['count' => count($ujianArray)]);

            return response()->json($ujianArray, 200, [
                'Content-Type' => 'application/json'
            ]);

        } catch (\Exception $e) {
            Log::error('Error in ujianTersedia: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'error' => 'Terjadi kesalahan saat memuat data ujian: ' . $e->getMessage(),
                'success' => false
            ], 500);
        }
    }

    // Keep your existing methods but rename for clarity
    public function getAvailableExams()
    {
        try {
            $ujian = DB::table('ujian')
                ->join('unit', 'ujian.unit_id', '=', 'unit.unit_id')
                ->join('pelatih', 'ujian.pelatih_id', '=', 'pelatih.pelatih_id')
                ->select(
                    'ujian.*',
                    'unit.nama_unit',
                    'pelatih.nama_pelatih'
                )
                ->where('ujian.status_ujian', 'dijadwalkan')
                ->where('ujian.tanggal_ujian', '>', now())
                ->orderBy('ujian.tanggal_ujian', 'asc')
                ->get();
                
            return response()->json($ujian);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to fetch exams: ' . $e->getMessage()], 500);
        }
    }
    
    // Add method for student exams - matches the route /murid/ujian/{murid_id}
    public function ujianMurid($muridId)
    {
        try {
            Log::info('ujianMurid called for murid_id: ' . $muridId);
            
            $ujian = DB::table('pendaftaran_ujian')
                ->join('ujian', 'pendaftaran_ujian.ujian_id', '=', 'ujian.ujian_id')
                ->leftJoin('unit', 'ujian.unit_id', '=', 'unit.unit_id')
                ->leftJoin('pelatih', 'ujian.pelatih_id', '=', 'pelatih.pelatih_id')
                ->select(
                    'pendaftaran_ujian.id',
                    'pendaftaran_ujian.tanggal_daftar',
                    'pendaftaran_ujian.status_pendaftaran',
                    'pendaftaran_ujian.status_pembayaran',
                    'pendaftaran_ujian.tanggal_bayar',
                    'pendaftaran_ujian.catatan_pendaftaran',
                    'ujian.nama_ujian',
                    'ujian.tanggal_ujian',
                    'ujian.waktu_mulai',
                    'ujian.waktu_selesai',
                    'ujian.sabuk_dari',
                    'ujian.sabuk_ke',
                    'ujian.biaya_ujian',
                    'unit.nama_unit',
                    'pelatih.nama_pelatih'
                )
                ->where('pendaftaran_ujian.murid_id', $muridId)
                ->orderBy('ujian.tanggal_ujian', 'desc')
                ->get();
                
            Log::info('Student ujian data retrieved', ['count' => $ujian->count()]);
            
            return response()->json($ujian->toArray());
        } catch (\Exception $e) {
            Log::error('Error in ujianMurid: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to fetch student exams: ' . $e->getMessage()], 500);
        }
    }
    
    public function getStudentExams($muridId)
    {
        // Redirect to new method name for backward compatibility
        return $this->ujianMurid($muridId);
    }

    public function bayar($id) {
        $pendaftaran = PendaftaranUjian::findOrFail($id);
        
        $pendaftaran->update([
            'status_pembayaran' => 'sudah_bayar',
            'status_pendaftaran' => 'diterima', // Auto-accept when paid
            'tanggal_bayar' => request('tanggal_bayar')
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Pembayaran berhasil dikonfirmasi. Status berubah menjadi Diterima.'
        ]);
    }


}