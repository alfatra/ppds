<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\DiagnosaController;

Route::middleware('api')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::get('/diagnosa/search', [DiagnosaController::class, 'getDiagnosaList']);
    Route::get('/diagnosa', [DiagnosaController::class, 'getDiagnosaList']);

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

    // Endpoint untuk fetch patient registration dari API eksternal dengan HMAC authentication
    // Mengikuti pattern dari CodeIgniter Model_API_Medinfras
    Route::get('/pasien', function (Request $request) {
        $apiUrl = 'http://192.168.10.33/medinfrasapi/workshop/api/registration/base/information/detail2';
        $consumerId = env('API_CONSUMER_ID');
        $consumerPassword = env('API_CONSUMER_PASSWORD');
        
        // Get search parameters
        $registrationNo = $request->query('registrationNo', $request->query('regNo', ''));
        $medicalNo = $request->query('medicalNo', $request->query('medicalNumber', ''));
        $paramedicCode = $request->query('paramedicCode', $request->query('dokter', ''));
        $departmentID = $request->query('departmentID', $request->query('department', 'OUTPATIENT'));
        $periodeRegistrationDate = $request->query('periodeRegistrationDate', $request->query('periode', date('Y-m-d')));

        // Extract date from registration number if format is OPR/YYYYMMDD/XXXXX
        if ($registrationNo && preg_match('/^OPR\/(\d{8})\//', $registrationNo, $matches)) {
            $dateStr = $matches[1];
            $periodeRegistrationDate = substr($dateStr, 0, 4) . '-' . substr($dateStr, 4, 2) . '-' . substr($dateStr, 6, 2);
            Log::info('Extracted registration date from registration number', ['registrationNo' => $registrationNo, 'periodeRegistrationDate' => $periodeRegistrationDate]);
        }

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

            // Build URL dengan parameter sesuai API requirement (CI model: skip empty params)
            // Parameter order: DepartmentID, periodeRegistrationDate, registrationNo, medicalNo, paramedicCode
            $queryParts = [];
            if ($departmentID) {
                $queryParts['DepartmentID'] = $departmentID;
            }
            if ($periodeRegistrationDate) {
                $queryParts['periodeRegistrationDate'] = $periodeRegistrationDate;
            }
            if ($registrationNo) {
                $queryParts['registrationNo'] = $registrationNo;
            }
            if ($medicalNo) {
                $queryParts['medicalNo'] = $medicalNo;
            }
            if ($paramedicCode) {
                $queryParts['paramedicCode'] = $paramedicCode;
            }

            $fullUrl = $apiUrl . '?' . http_build_query($queryParts);

            Log::info('Patient Registration API Request (CI model - skip empty params)', [
                'url' => $fullUrl,
                'params' => $queryParts
            ]);

            $response = Http::withHeaders($headers)->timeout(30)->get($fullUrl);

            Log::info('Patient Registration API Response', [
                'status' => $response->status(),
                'body_sample' => substr($response->body(), 0, 500)
            ]);

            if ($response->successful()) {
                $apiResponse = $response->json();
                
                Log::debug('Patient Registration Full Response', ['response' => $apiResponse]);
                
                // Check if Status is SUCCESS
                if (isset($apiResponse['Status']) && $apiResponse['Status'] !== 'SUCCESS') {
                    Log::warning('Patient Registration API returned non-SUCCESS status', [
                        'status' => $apiResponse['Status'],
                        'remarks' => $apiResponse['Remarks'] ?? 'No remarks'
                    ]);

                    return response()->json([
                        'success' => false,
                        'data' => [],
                        'message' => $apiResponse['Remarks'] ?? 'Tidak ada data pasien',
                        'api_status' => $apiResponse['Status'] ?? 'UNKNOWN',
                        'api_remarks' => $apiResponse['Remarks'] ?? ''
                    ], 200);
                }

                // Parse Data field - perlu di-decode karena berupa JSON string
                $dataField = $apiResponse['Data'] ?? null;
                
                if (is_null($dataField)) {
                    Log::info('Patient Registration API returned null Data field');

                    return response()->json([
                        'success' => false,
                        'data' => [],
                        'message' => 'Tidak ada data pasien yang ditemukan',
                        'api_status' => $apiResponse['Status'] ?? null,
                        'api_remarks' => $apiResponse['Remarks'] ?? null
                    ], 200);
                }
                
                // Jika Data adalah string JSON (sesuai pattern API), decode-nya
                if (is_string($dataField)) {
                    $patientList = json_decode($dataField, true);
                    if (json_last_error() !== JSON_ERROR_NONE) {
                        Log::error('JSON decode error: ' . json_last_error_msg());
                        return response()->json([
                            'success' => false,
                            'data' => [],
                            'message' => 'Error parsing data',
                            'json_error' => json_last_error_msg()
                        ], 500);
                    }
                } else {
                    $patientList = $dataField;
                }

                // Ensure it's an array
                if (!is_array($patientList)) {
                    $patientList = [$patientList];
                }

                Log::info('Patient list parsed successfully', ['count' => count($patientList)]);

                return response()->json([
                    'success' => true,
                    'data' => $patientList,
                    'count' => count($patientList)
                ]);
            }

            // Jika response tidak successful (status code 4xx, 5xx)
            Log::error('Patient Registration API request failed', [
                'status' => $response->status(),
                'body_sample' => substr($response->body(), 0, 500)
            ]);

            $errorBody = null;
            try {
                $errorBody = $response->json();
            } catch (\Exception $e) {
                // ignore parse errors
            }

            return response()->json([
                'success' => false,
                'data' => [],
                'message' => $errorBody['Remarks'] ?? 'Tidak ada data pasien',
                'api_status' => $errorBody['Status'] ?? null,
                'api_remarks' => $errorBody['Remarks'] ?? substr($response->body(), 0, 500)
            ], $response->status());

        } catch (\Exception $e) {
            Log::error('Exception when calling Patient Registration API: ' . $e->getMessage(), [
                'exception' => get_class($e),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'data' => [],
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    });

    // Direct registrasi detail endpoint - mirrors CI getRegistrasi() exactly
    // This calls rssm/api/registration/base/{RegistrationNo} directly WITHOUT URL encoding
    Route::get('/registrasi-direct/{registrationNo}', function (Request $request, $registrationNo) {
        $consumerId = env('API_CONSUMER_ID');
        $consumerPassword = env('API_CONSUMER_PASSWORD');

        try {
            // Build URL WITHOUT encoding slashes (match CI behavior)
            $apiUrl = 'http://192.168.10.33/medinfrasapi/rssm/api/registration/base/' . $registrationNo;

            // HMAC authentication - same as CI
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

            Log::info('Direct Registrasi API Request (CI getRegistrasi mirror)', [
                'url' => $apiUrl,
                'registrationNo' => $registrationNo,
                'method' => 'GET'
            ]);

            $response = Http::withHeaders($headers)->timeout(30)->get($apiUrl);

            Log::info('Direct Registrasi API Response', [
                'status' => $response->status(),
                'body_sample' => substr($response->body(), 0, 500)
            ]);

            if ($response->successful()) {
                $apiResponse = $response->json();

                // Parse Data field - same as CI: decode because it's JSON string
                $dataField = $apiResponse['Data'] ?? null;

                if (is_null($dataField)) {
                    return response()->json([
                        'success' => false,
                        'data' => [],
                        'message' => 'Data registrasi tidak ditemukan'
                    ]);
                }

                // Decode JSON string in Data field
                if (is_string($dataField)) {
                    $registrasiData = json_decode($dataField, true);
                    if (json_last_error() !== JSON_ERROR_NONE) {
                        Log::error('JSON decode error: ' . json_last_error_msg());
                        return response()->json([
                            'success' => false,
                            'data' => [],
                            'message' => 'Error parsing data',
                            'json_error' => json_last_error_msg()
                        ]);
                    }
                } else {
                    $registrasiData = $dataField;
                }

                Log::info('Direct Registrasi detail parsed successfully', ['data_type' => gettype($registrasiData)]);

                return response()->json([
                    'success' => true,
                    'data' => $registrasiData,
                    'source' => 'direct_rssm'
                ]);
            }

            Log::error('Direct Registrasi API request failed', [
                'status' => $response->status(),
                'body_sample' => substr($response->body(), 0, 500)
            ]);

            return response()->json([
                'success' => false,
                'data' => [],
                'message' => 'API request failed with status ' . $response->status()
            ], $response->status());

        } catch (\Exception $e) {
            Log::error('Exception in Direct Registrasi: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'data' => [],
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    })->where('registrationNo', '.*');

    // Endpoint tambahan untuk fetch detail registrasi berdasarkan nomor registrasi
    // Pattern: GET /api/registrasi/REG-2024-001
    Route::get('/registrasi/{registrationNo}', function (Request $request, $registrationNo) {
        // Encode registrationNo so slashes become %2F (external API expects encoded value)
        $apiUrl = 'http://192.168.10.33/medinfrasapi/rssm/api/registration/base/' . rawurlencode($registrationNo);
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

            Log::info('Get Registrasi Detail API Request', [
                'url' => $apiUrl,
                'registrationNo' => $registrationNo
            ]);

            $response = Http::withHeaders($headers)->timeout(30)->get($apiUrl);

            Log::info('Get Registrasi Detail API Response', [
                'status' => $response->status(),
                'body_sample' => substr($response->body(), 0, 500)
            ]);

            if ($response->successful()) {
                $apiResponse = $response->json();
                
                // Parse Data field - decode karena berupa JSON string
                $dataField = $apiResponse['Data'] ?? null;
                
                if (is_null($dataField)) {
                    return response()->json([
                        'success' => false,
                        'data' => [],
                        'message' => 'Data registrasi tidak ditemukan'
                    ]);
                }
                
                if (is_string($dataField)) {
                    $registrasiData = json_decode($dataField, true);
                    if (json_last_error() !== JSON_ERROR_NONE) {
                        Log::error('JSON decode error: ' . json_last_error_msg());
                        return response()->json([
                            'success' => false,
                            'data' => [],
                            'message' => 'Error parsing data'
                        ]);
                    }
                } else {
                    $registrasiData = $dataField;
                }

                Log::info('Registrasi detail parsed successfully');

                return response()->json([
                    'success' => true,
                    'data' => $registrasiData
                ]);
            }

            Log::warning('Get Registrasi Detail API request failed, attempting fallback to detail2', [
                'status' => $response->status()
            ]);

            // Fallback: try the workshop/detail2 endpoint - matching CI implementation exactly
            try {
                $fallbackUrl = 'http://192.168.10.33/medinfrasapi/workshop/api/registration/base/information/detail2';
                
                // Extract date from registration number if format is OPR/YYYYMMDD/XXXXX
                $fallbackPeriodDate = $request->query('periodeRegistrationDate', date('Y-m-d'));
                if (preg_match('/^OPR\/(\d{8})\//', $registrationNo, $matches)) {
                    // Convert YYYYMMDD to YYYY-MM-DD
                    $dateStr = $matches[1];
                    $fallbackPeriodDate = substr($dateStr, 0, 4) . '-' . substr($dateStr, 4, 2) . '-' . substr($dateStr, 6, 2);
                    Log::info('Extracted registration date from number', ['registrationNo' => $registrationNo, 'periodeRegistrationDate' => $fallbackPeriodDate]);
                }

                // Build query string following CI model order: DepartmentID, periodeRegistrationDate, registrationNo, medicalNo, paramedicCode
                // Only include non-empty parameters
                $queryParts = [];
                $departmentID = $request->query('departmentID', 'OUTPATIENT');
                if ($departmentID) {
                    $queryParts['DepartmentID'] = $departmentID;
                }
                if ($fallbackPeriodDate) {
                    $queryParts['periodeRegistrationDate'] = $fallbackPeriodDate;
                }
                if ($registrationNo) {
                    $queryParts['registrationNo'] = $registrationNo;
                }
                $medicalNo = $request->query('medicalNo', '');
                if ($medicalNo) {
                    $queryParts['medicalNo'] = $medicalNo;
                }
                $paramedicCode = $request->query('paramedicCode', '');
                if ($paramedicCode) {
                    $queryParts['paramedicCode'] = $paramedicCode;
                }

                $fallbackQuery = http_build_query($queryParts);
                $fallbackFullUrl = $fallbackUrl . '?' . $fallbackQuery;

                Log::info('Fallback detail2 URL built', [
                    'url' => $fallbackFullUrl,
                    'queryParams' => $queryParts
                ]);

                Log::info('Fallback Patient Registration API Request', ['url' => $fallbackFullUrl]);

                $fallbackResp = Http::withHeaders($headers)->timeout(30)->get($fallbackFullUrl);

                Log::info('Fallback Patient Registration API Response', [
                    'status' => $fallbackResp->status(),
                    'body_sample' => substr($fallbackResp->body(), 0, 500)
                ]);

                if ($fallbackResp->successful()) {
                    $fbApiResponse = $fallbackResp->json();

                    if (isset($fbApiResponse['Status']) && $fbApiResponse['Status'] !== 'SUCCESS') {
                        Log::warning('Fallback detail2 returned non-SUCCESS', ['status' => $fbApiResponse['Status'], 'remarks' => $fbApiResponse['Remarks'] ?? '']);

                        return response()->json([
                            'success' => false,
                            'data' => [],
                            'message' => $fbApiResponse['Remarks'] ?? 'Tidak ada data pasien (fallback)'
                        ]);
                    }

                    $dataField = $fbApiResponse['Data'] ?? null;
                    if (is_null($dataField)) {
                        return response()->json([
                            'success' => false,
                            'data' => [],
                            'message' => 'Tidak ada data pasien yang ditemukan (fallback)'
                        ]);
                    }

                    if (is_string($dataField)) {
                        $patientList = json_decode($dataField, true);
                        if (json_last_error() !== JSON_ERROR_NONE) {
                            Log::error('Fallback JSON decode error: ' . json_last_error_msg());
                            return response()->json([
                                'success' => false,
                                'data' => [],
                                'message' => 'Error parsing fallback data'
                            ]);
                        }
                    } else {
                        $patientList = $dataField;
                    }

                    if (!is_array($patientList)) {
                        $patientList = [$patientList];
                    }

                    return response()->json([
                        'success' => true,
                        'data' => $patientList,
                        'count' => count($patientList),
                        'source' => 'fallback_detail2'
                    ]);
                }

                Log::error('Fallback detail2 request failed', ['status' => $fallbackResp->status()]);

                return response()->json([
                    'success' => false,
                    'data' => [],
                    'message' => 'API request failed (primary and fallback)'
                ], 502);

            } catch (\Exception $ex) {
                Log::error('Exception during fallback detail2: ' . $ex->getMessage());
                return response()->json([
                    'success' => false,
                    'data' => [],
                    'message' => 'Exception during fallback: ' . $ex->getMessage()
                ], 500);
            }

        } catch (\Exception $e) {
            Log::error('Exception in Get Registrasi Detail: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'data' => [],
                'message' => 'Error: ' . $e->getMessage()
            ]);
        }
    })->where('registrationNo', '.*');
});
