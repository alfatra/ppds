<?php

namespace App\Console\Commands;

use App\Models\Diagnosis;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FetchDiagnosisData extends Command
{
    protected $signature = 'diagnosis:fetch';
    protected $description = 'Fetch diagnosis data from external API and save to database';

    public function handle()
    {
        $this->info('Fetching diagnosis data from API...');

        $apiUrl = env('API_DIAGNOSIS_URL');
        $consumerID = env('API_CONSUMER_ID');
        $consumerPassword = env('API_CONSUMER_PASSWORD');

        // Debug untuk cek env
        $this->info("API URL: " . $apiUrl);
        $this->info("Consumer ID: " . $consumerID);

        if (!$apiUrl || !$consumerID || !$consumerPassword) {
            $this->error("Konfigurasi kredensial API di sisi server belum diatur.");
            return 1;
        }

        try {

         $timestamp = floor(time());

$data = $timestamp . $consumerID;

$signature = base64_encode(
    hash_hmac('sha256', $data, $consumerPassword, true)
);
            $response = Http::timeout(30)
                ->withHeaders([
                      'X-cons-id' => $consumerID,
        'X-timestamp' => $timestamp,
        'X-signature' => $signature
    ])
                    
                ->get($apiUrl);

            if ($response->failed()) {
                $this->error('API returned status ' . $response->status());
                return 1;
            }

            $apiData = $response->json();

            if (!$apiData || $apiData['Status'] !== 'SUCCESS') {
                $this->error('API Error: ' . ($apiData['Remarks'] ?? 'Unknown error'));
                return 1;
            }

            $diagnoses = json_decode($apiData['Data'], true);

            if (empty($diagnoses)) {
                $this->error('No diagnosis data received from API');
                return 1;
            }

            Diagnosis::truncate();

            foreach ($diagnoses as $item) {

                Diagnosis::create([
                    'diagnose_id' => $item['DiagnoseID'] ?? '',
                    'diagnose_name' => $item['DiagnoseName'] ?? '',
                    'bp_js_reference_info' => $item['BPJSReferenceInfo'] ?? null,
                    'is_chronic_disease' => $item['IsChronicDisease'] ?? false,
                    'is_infectious' => $item['IsInfectious'] ?? false,
                    'is_disease' => $item['IsDisease'] ?? false,
                    'is_nutrition_diagnosis' => $item['IsNutritionDiagnosis'] ?? false,
                    'is_external_diagnosis' => $item['IsExternalDiagnosis'] ?? false,
                    'is_potential_prb' => $item['IsPotentialPRB'] ?? false,
                    'is_covered_by_bpjs' => $item['IsCoveredByBPJS'] ?? false,
                ]);

            }

            $this->info("✓ Successfully saved " . count($diagnoses) . " diagnoses");

            return 0;

        } catch (\Exception $e) {

            $this->error("Error: " . $e->getMessage());

            Log::error("Fetch diagnosis error", [
                'message' => $e->getMessage()
            ]);

            return 1;
        }
    }
}