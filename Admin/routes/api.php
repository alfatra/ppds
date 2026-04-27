<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Api\DiagnosisController;

Route::middleware('api')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::get('/diagnosa/search', [DiagnosisController::class, 'index']);
    Route::get('/diagnosa', [DiagnosisController::class, 'index']);

    // Endpoint untuk fetch dokter dari API eksternal dengan HMAC authentication
    Route::get('/dokter', function () {
        $apiUrl = env('API_PARAMEDIC_URL', 'http://192.168.10.33/medinfrasapi/rssm/api/Paramedic/base/list');
        $consumerId = env('API_CONSUMER_ID');
        $consumerPassword = env('API_CONSUMER_PASSWORD');

        try {
            // Tambahkan HMAC authentication
            $timestamp = time();
            $dataToSign = $timestamp . $consumerId;
            $signature = base64_encode(hash_hmac('sha256', $dataToSign, $consumerPassword, true));

            $headers = [
                'X-cons-id' => $consumerId,
                'X-timestamp' => $timestamp,
                'X-signature' => $signature,
                'Accept' => 'application/json',
                'Content-Type' => 'application/json'
            ];

            Log::info('Paramedic API Request', [
                'url' => $apiUrl,
                'headers' => $headers
            ]);

            $response = Http::withHeaders($headers)->timeout(30)->get($apiUrl);

            Log::info('Paramedic API Response', [
                'status' => $response->status(),
                'body_sample' => substr($response->body(), 0, 500)
            ]);

            if ($response->successful()) {
                $apiResponse = $response->json();
                
                // Check if Status is SUCCESS
                if ($apiResponse['Status'] !== 'SUCCESS') {
                    Log::warning('Paramedic API returned non-SUCCESS status', [
                        'status' => $apiResponse['Status'],
                        'remarks' => $apiResponse['Remarks'] ?? ''
                    ]);
                    
                    return response()->json([
                        'success' => false,
                        'data' => [],
                        'message' => $apiResponse['Remarks'] ?? 'API returned non-SUCCESS status'
                    ], 400);
                }

                // Parse Data field - bisa berupa string JSON atau null
                $dataField = $apiResponse['Data'] ?? null;
                
                if (is_null($dataField)) {
                    Log::error('Paramedic API returned null Data field');
                    
                    return response()->json([
                        'success' => false,
                        'data' => [],
                        'message' => 'API returned null data - check authentication'
                    ], 401);
                }
                
                // Jika Data adalah string JSON, decode-nya
                if (is_string($dataField)) {
                    $dokterList = json_decode($dataField, true);
                    if (json_last_error() !== JSON_ERROR_NONE) {
                        Log::error('JSON decode error: ' . json_last_error_msg());
                        return response()->json([
                            'success' => false,
                            'data' => [],
                            'message' => 'JSON decode error: ' . json_last_error_msg()
                        ], 500);
                    }
                } else {
                    $dokterList = $dataField;
                }

                if (!is_array($dokterList)) {
                    Log::error('Decoded data is not an array', ['type' => gettype($dokterList)]);
                    return response()->json([
                        'success' => false,
                        'data' => [],
                        'message' => 'Decoded data is not an array'
                    ], 500);
                }

                Log::info('Paramedic list parsed successfully', ['count' => count($dokterList)]);

                return response()->json([
                    'success' => true,
                    'data' => $dokterList,
                    'count' => count($dokterList)
                ]);
            }

            Log::error('Paramedic API request failed', [
                'status' => $response->status(),
                'body' => $response->body()
            ]);

            return response()->json([
                'success' => false,
                'data' => [],
                'message' => 'API request failed with status ' . $response->status()
            ], $response->status());

        } catch (\Exception $e) {
            Log::error('Exception when calling Paramedic API: ' . $e->getMessage(), [
                'exception' => get_class($e)
            ]);
            
            return response()->json([
                'success' => false,
                'data' => [],
                'message' => 'Exception: ' . $e->getMessage()
            ], 500);
        }
    });
});
