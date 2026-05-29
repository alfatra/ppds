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
        // Mengambil konfigurasi dari config/apis.php dengan fallback ke .env
        $apiUrl = config('apis.diagnosa.url') ?: env('API_DIAGNOSA_URL', 'http://192.168.10.33/medinfrasapi/rssm/api/diagnose/list'); // Sesuaikan default URL jika berbeda
        $consumerId = config('apis.diagnosa.consumer_id') ?: env('API_CONSUMER_ID');
        $consumerPassword = config('apis.diagnosa.consumer_password') ?: env('API_CONSUMER_PASSWORD');

        if (!$consumerId || !$consumerPassword || !$apiUrl) {
            Log::error('Kredensial atau URL API Diagnosa tidak ditemukan. Pastikan sudah diatur di file .env.');
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

    Log::info('Diagnosa Raw Response', $dataArray);

    // Pastikan key Data ada
    if (!isset($dataArray['Data'])) {

        Log::error('Key Data tidak ditemukan', [
            'response' => $dataArray
        ]);

        return response()->json([
            'success' => false,
            'message' => 'Format response API tidak valid',
            'response' => $dataArray
        ], 200);
    }

    // Jika Data kosong/null
    if (empty($dataArray['Data'])) {

        return response()->json([
            'success' => true,
            'data' => [],
            'count' => 0
        ]);
    }

    // Decode JSON string dari API
    $decodedData = json_decode($dataArray['Data'], true);

    // CEK ERROR JSON
    if (json_last_error() !== JSON_ERROR_NONE) {

        Log::error('JSON Decode Error', [
            'error' => json_last_error_msg(),
            'raw_data' => substr($dataArray['Data'], 0, 1000)
        ]);

        return response()->json([
            'success' => false,
            'message' => 'Gagal membaca data diagnosa',
            'json_error' => json_last_error_msg()
        ], 200);
    }

    // Pastikan array
    if (!is_array($decodedData)) {

        return response()->json([
            'success' => false,
            'message' => 'Format data diagnosa bukan array'
        ], 200);
    }

    // Bersihkan null
    $filteredData = array_filter($decodedData, function ($item) {
        return $item !== null;
    });

    $finalData = array_values($filteredData);

    return response()->json([
        'success' => true,
        'data' => $finalData,
        'count' => count($finalData)
    ]);
}

            // Jika request gagal, catat error dan kembalikan pesan error ke frontend.
            Log::error('Proxy Gagal: API Diagnosa eksternal mengembalikan error.', [
                'status' => $response->status(),
                'body' => $response->body()
            ]);

            return response()->json(['success' => false, 'message' => 'Gagal mengambil data diagnosa dari server sumber. Status: ' . $response->status()], 200);

        } catch (\Throwable $e) {
            // Menangani error koneksi (misal: API tidak bisa dijangkau)
            Log::error('Error koneksi ke API Diagnosa: ' . $e->getMessage());

            return response()->json(['success' => false, 'message' => 'Tidak dapat terhubung ke server diagnosa.'], 503);
        }
    }
}
