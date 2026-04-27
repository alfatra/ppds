<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Diagnosis;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class DiagnosaController extends Controller
{
    /**
     * Bertindak sebagai proxy untuk mengambil daftar diagnosa dari API eksternal.
     */
    public function getDiagnosaList()
    {
        // Mengambil konfigurasi dari config/apis.php (cara yang benar di Laravel)
        $apiUrl = config('apis.diagnosa.url');
        $consumerId = config('apis.diagnosa.consumer_id');
        $consumerPassword = config('apis.diagnosa.consumer_password');

        if (!$consumerId || !$consumerPassword) {
            Log::error('Kredensial API Diagnosa tidak ditemukan. Pastikan DIAGNOSA_CONSUMER_ID dan DIAGNOSA_CONSUMER_PASSWORD ada di file .env.');
            return response()->json(['success' => false, 'message' => 'Konfigurasi kredensial API di sisi server belum diatur.'], 500);
        }

        try {
            // --- Implementasi Otentikasi HMAC Signature ---
            // 1. Dapatkan timestamp saat ini (Unix timestamp)
            $timestamp = time();
            // Menggunakan variabel $consumerId yang sudah didefinisikan di atas
            $dataToSign = $timestamp . $consumerId;
 
            $signature = base64_encode(
                hash_hmac('sha256', $dataToSign, $consumerPassword, true)
            );
 
            $response = Http::timeout(30)->withHeaders([
                'X-cons-id' => $consumerId,
                'X-timestamp' => $timestamp,
                'X-signature' => $signature
            ])->get($apiUrl);

            if ($response->successful()) {
                $dataArray = $response->json();

                // Data utama ada di dalam key 'Data' dan perlu di-decode lagi.
                if (!isset($dataArray['Data'])) {
                    Log::error('Proxy Gagal: Respons API Diagnosa tidak memiliki key "Data".', ['response' => $dataArray]);
                    return response()->json(['success' => false, 'message' => 'Format respons dari server diagnosa tidak valid.'], 500);
                }

                $responseData = json_decode($dataArray['Data'], true);

                $data = is_array($responseData) ? $responseData : [];
                $filteredData = array_filter($data, fn($item) => $item !== null);

                return response()->json([
                    'success' => true,
                    'data' => array_values($filteredData),
                ]);
            }

            // Jika request gagal, catat error dan kembalikan pesan error ke frontend.
            Log::error('Proxy Gagal: API Diagnosa eksternal mengembalikan error.', [
                'status' => $response->status(),
                'body' => $response->body()
            ]);

            return response()->json(['success' => false, 'message' => 'Gagal mengambil data diagnosa dari server sumber. Status: ' . $response->status()], 500);

        } catch (\Exception $e) {
            // Menangani error koneksi (misal: API tidak bisa dijangkau)
            Log::error('Error koneksi ke API Diagnosa: ' . $e->getMessage());

            return response()->json(['success' => false, 'message' => 'Tidak dapat terhubung ke server diagnosa.'], 503);
        }
    }
}
