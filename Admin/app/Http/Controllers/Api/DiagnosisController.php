<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class DiagnosisController extends Controller
{
    /**
     * Return list of diagnoses from master API.
     * Accepts optional query parameter `q` for search term.
     */
    public function index(Request $request)
    {
        $q = $request->input('q', '');
        $url = config('services.diagnosis_api.url');
        
        if (!$url) {
            return response()->json([
                'success' => false,
                'message' => 'Diagnosis API URL not configured',
                'data' => []
            ], 500);
        }

        try {
            // Request ke API master
            $response = Http::timeout(10)->get($url, ['q' => $q]);

            if ($response->successful()) {
                $data = $response->json();
                
                // Format response dari API - sesuaikan dengan structure API Anda
                // Jika API mengembalikan format: { "data": [...] }
                if (isset($data['data'])) {
                    $diagnoses = $data['data'];
                }
                // Jika API mengembalikan format: { "result": [...] }
                elseif (isset($data['result'])) {
                    $diagnoses = $data['result'];
                }
                // Jika API mengembalikan langsung array
                elseif (is_array($data)) {
                    $diagnoses = $data;
                }
                else {
                    $diagnoses = [];
                }

                // Filter hasil jika ada query search
                if ($q) {
                    $diagnoses = collect($diagnoses)->filter(function ($item) use ($q) {
                        $searchableFields = ['nm_diagnosa', 'kd_diagnosa', 'name', 'code'];
                        
                        foreach ($searchableFields as $field) {
                            if (isset($item[$field]) && stripos($item[$field], $q) !== false) {
                                return true;
                            }
                        }
                        return false;
                    })->values()->all();
                }

                return response()->json([
                    'success' => true,
                    'data' => array_slice($diagnoses, 0, 50) // Limit 50 hasil
                ]);
            }

            Log::error('Diagnosis API Error', [
                'status' => $response->status(),
                'body' => $response->body()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch diagnoses from API',
                'data' => []
            ], $response->status());

        } catch (\Exception $e) {
            Log::error('Diagnosis API Exception', [
                'message' => $e->getMessage(),
                'url' => $url
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error connecting to diagnosis API: ' . $e->getMessage(),
                'data' => []
            ], 500);
        }
    }
}
