<?php

namespace Database\Seeders;

use App\Models\Diagnosis;
use Illuminate\Database\Seeder;

class DiagnosisSeeder extends Seeder
{
    public function run()
    {
        // Clear existing data
        Diagnosis::truncate();

        // Sample diagnosis data from ICD-10
        $diagnoses = [
            ['diagnose_id' => 'A01.0', 'diagnose_name' => 'Demam Tifoid'],
            ['diagnose_id' => 'A02.0', 'diagnose_name' => 'Sakit Paratifoid'],
            ['diagnose_id' => 'A03.0', 'diagnose_name' => 'Basil Disentri'],
            ['diagnose_id' => 'A04.0', 'diagnose_name' => 'Infeksi E.coli'],
            ['diagnose_id' => 'A05.0', 'diagnose_name' => 'Food Poisoning (Keracunan Makanan)'],
            ['diagnose_id' => 'B01.9', 'diagnose_name' => 'Cacar Air (Varicella)'],
            ['diagnose_id' => 'B05.9', 'diagnose_name' => 'Campak (Measles)'],
            ['diagnose_id' => 'B06.9', 'diagnose_name' => 'Rubella (German Measles)'],
            ['diagnose_id' => 'I10', 'diagnose_name' => 'Hipertensi (Tekanan Darah Tinggi)'],
            ['diagnose_id' => 'I11.0', 'diagnose_name' => 'Hipertensi dengan Penyakit Jantung'],
            ['diagnose_id' => 'I50.1', 'diagnose_name' => 'Gagal Jantung Kiri'],
            ['diagnose_id' => 'E11.9', 'diagnose_name' => 'Diabetes Melitus Tipe 2'],
            ['diagnose_id' => 'E10.9', 'diagnose_name' => 'Diabetes Melitus Tipe 1'],
            ['diagnose_id' => 'E66.9', 'diagnose_name' => 'Obesitas (Kegemukan)'],
            ['diagnose_id' => 'F32.9', 'diagnose_name' => 'Depresi Berat'],
            ['diagnose_id' => 'F41.1', 'diagnose_name' => 'Gangguan Kecemasan'],
            ['diagnose_id' => 'J44.9', 'diagnose_name' => 'PPOK (Penyakit Paru Obstruktif Kronis)'],
            ['diagnose_id' => 'J45.9', 'diagnose_name' => 'Asma'],
            ['diagnose_id' => 'K21.0', 'diagnose_name' => 'GERD (Refluks Asam)'],
            ['diagnose_id' => 'K29.7', 'diagnose_name' => 'Gastritis'],
            ['diagnose_id' => 'N18.3', 'diagnose_name' => 'Penyakit Ginjal Kronis Stadium 3'],
            ['diagnose_id' => 'N39.0', 'diagnose_name' => 'Infeksi Saluran Kemih'],
            ['diagnose_id' => 'M79.3', 'diagnose_name' => 'Mialgia (Nyeri Otot)'],
            ['diagnose_id' => 'M25.5', 'diagnose_name' => 'Nyeri Sendi'],
        ];

        // Insert data into database
        foreach ($diagnoses as $diagnosis) {
            Diagnosis::create($diagnosis);
        }

        $this->command->info('✓ ' . count($diagnoses) . ' diagnosis records seeded successfully!');
    }
}
