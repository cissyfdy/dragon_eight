<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
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
                ->whereNotIn('status_pendaftaran', ['dibatalkan']) // Exclude cancelled registrations
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
            Log::error('Error in store method: ' . $e->getMessage());
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

            // Check if already paid - prevent cancellation if paid
            if ($pendaftaran->status_pembayaran === 'sudah_bayar') {
                return response()->json([
                    'success' => false,
                    'message' => 'Tidak dapat membatalkan pendaftaran yang sudah dibayar'
                ], 400);
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
            Log::error('Error in cancel method: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function confirmPayment($id, Request $request)
    {
        try {
            // Validate input including the new fields
            $request->validate([
                'tanggal_bayar' => 'required|date',
                'status_pendaftaran' => 'nullable|string|in:terdaftar,diterima,ditolak',
                'status_pembayaran' => 'nullable|string|in:belum_bayar,sudah_bayar,refund'
            ]);

            $pendaftaran = DB::table('pendaftaran_ujian')->where('id', $id)->first();

            if (!$pendaftaran) {
                return response()->json([
                    'success' => false,
                    'message' => 'Pendaftaran tidak ditemukan'
                ], 404);
            }

            // Check if already paid
            if ($pendaftaran->status_pembayaran === 'sudah_bayar') {
                return response()->json([
                    'success' => false,
                    'message' => 'Pendaftaran sudah dalam status sudah bayar'
                ], 400);
            }

            // Prepare update data
            $updateData = [
                'status_pembayaran' => $request->status_pembayaran ?? 'sudah_bayar',
                'tanggal_bayar' => $request->tanggal_bayar,
                'updated_at' => now()
            ];

            // If status_pendaftaran is provided, update it as well
            if ($request->has('status_pendaftaran')) {
                $updateData['status_pendaftaran'] = $request->status_pendaftaran;
            } else {
                // Auto-set to 'diterima' when payment is confirmed
                $updateData['status_pendaftaran'] = 'diterima';
            }

            // Update payment and registration status
            DB::table('pendaftaran_ujian')
                ->where('id', $id)
                ->update($updateData);

            return response()->json([
                'success' => true,
                'message' => 'Pembayaran berhasil dikonfirmasi dan status berubah menjadi diterima'
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak valid: ' . implode(', ', $e->errors()['tanggal_bayar'] ?? $e->errors())
            ], 422);
        } catch (\Exception $e) {
            Log::error('Error in confirmPayment method: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan sistem: ' . $e->getMessage()
            ], 500);
        }
    }
}