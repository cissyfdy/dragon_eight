<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PendaftaranUjianController extends Controller
{
    public function store(Request $request)
    {
        try {
            $request->validate([
                'murid_id' => 'required|string',
                'ujian_id' => 'required|integer',
                'tanggal_daftar' => 'required|date',
                'catatan_pendaftaran' => 'nullable|string'
            ]);

            // Check if already registered
            $existing = DB::table('pendaftaran_ujian')
                ->where('murid_id', $request->murid_id)
                ->where('ujian_id', $request->ujian_id)
                ->first();

            if ($existing) {
                return response()->json([
                    'success' => false,
                    'message' => 'Anda sudah terdaftar untuk ujian ini'
                ], 400);
            }

            // Insert new registration
            DB::table('pendaftaran_ujian')->insert([
                'murid_id' => $request->murid_id,
                'ujian_id' => $request->ujian_id,
                'tanggal_daftar' => $request->tanggal_daftar,
                'status_pendaftaran' => 'terdaftar',
                'status_pembayaran' => 'belum_bayar',
                'catatan_pendaftaran' => $request->catatan_pendaftaran,
                'created_at' => now(),
                'updated_at' => now()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Pendaftaran ujian berhasil'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function cancel($id)
    {
        try {
            $pendaftaran = DB::table('pendaftaran_ujian')->where('id', $id)->first();

            if (!$pendaftaran) {
                return response()->json([
                    'success' => false,
                    'message' => 'Pendaftaran tidak ditemukan'
                ], 404);
            }

            // Update status to cancelled
            DB::table('pendaftaran_ujian')
                ->where('id', $id)
                ->update([
                    'status_pendaftaran' => 'dibatalkan',
                    'updated_at' => now()
                ]);

            return response()->json([
                'success' => true,
                'message' => 'Pendaftaran ujian berhasil dibatalkan'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function confirmPayment($id, Request $request)
    {
        try {
            $request->validate([
                'tanggal_bayar' => 'required|date'
            ]);

            $pendaftaran = DB::table('pendaftaran_ujian')->where('id', $id)->first();

            if (!$pendaftaran) {
                return response()->json([
                    'success' => false,
                    'message' => 'Pendaftaran tidak ditemukan'
                ], 404);
            }

            // Update payment status
            DB::table('pendaftaran_ujian')
                ->where('id', $id)
                ->update([
                    'status_pembayaran' => 'sudah_bayar',
                    'tanggal_bayar' => $request->tanggal_bayar,
                    'updated_at' => now()
                ]);

            return response()->json([
                'success' => true,
                'message' => 'Pembayaran berhasil dikonfirmasi'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }
    
}