<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MedicalActivitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $activities = [
            // Tindakan Keperawatan / Dasar
            ['name' => 'Menyuntikkan Injeksi', 'description' => 'Tindakan menyuntikkan obat secara intravena, intramuskular, subkutan, atau intrakutan.'],
            ['name' => 'Memasang Infus', 'description' => 'Tindakan pemasangan akses intravena untuk pemberian cairan atau obat.'],
            ['name' => 'Pemasangan Kateter', 'description' => 'Tindakan memasukkan selang kateter ke dalam kandung kemih (Kateterisasi Urine).'],
            ['name' => 'Pemasangan NGT', 'description' => 'Tindakan memasang selang Nasogastric Tube (NGT).'],
            ['name' => 'Perawatan Luka', 'description' => 'Tindakan membersihkan dan mengganti balutan luka (Wound Care).'],
            
            // Tindakan Bedah Minor / Prosedural Dasar
            ['name' => 'Hecting / Jahit Luka', 'description' => 'Tindakan menjahit luka robek atau luka pasca operasi.'],
            ['name' => 'Aff Hecting / Angkat Jahitan', 'description' => 'Tindakan mengangkat benang jahitan pada luka yang sudah mengering.'],
            ['name' => 'Insisi dan Drainase Abses', 'description' => 'Tindakan pembedahan kecil untuk mengeluarkan nanah dari abses.'],
            ['name' => 'Ekstraksi Kuku (Roserplasty)', 'description' => 'Tindakan pencabutan sebagian atau seluruh kuku.'],
            ['name' => 'Sirkumsisi', 'description' => 'Tindakan pemotongan kulup atau preputium (Khitan).'],
            ['name' => 'Ekstraksi Corpus Alienum', 'description' => 'Tindakan pengeluaran benda asing dari tubuh (misal pada mata, telinga, hidung, atau kulit).'],
            ['name' => 'Biopsi Jaringan', 'description' => 'Pengambilan sampel jaringan tubuh untuk pemeriksaan patologi anatomi.'],

            // Tindakan Kegawatdaruratan & Intensif
            ['name' => 'Resusitasi Jantung Paru (RJP)', 'description' => 'Tindakan penyelamatan nyawa pada pasien henti jantung dan henti napas (CPR).'],
            ['name' => 'Intubasi Endotrakeal', 'description' => 'Pemasangan pipa endotrakeal untuk mengamankan jalan napas.'],
            ['name' => 'Defibrilasi / Kardioversi', 'description' => 'Pemberian kejut listrik pada jantung untuk mengatasi aritmia mengancam nyawa.'],
            ['name' => 'Pemasangan CVC', 'description' => 'Pemasangan Central Venous Catheter untuk akses vena sentral.'],
            ['name' => 'Pemasangan WSD', 'description' => 'Pemasangan Water Sealed Drainage untuk menguras udara atau cairan dari rongga pleura.'],
            ['name' => 'Vena Seksi (Venesection)', 'description' => 'Tindakan pembedahan kecil untuk menemukan vena sebagai akses intravena darurat.'],
            
            // Tindakan Medis Khusus (Pungsi, dll)
            ['name' => 'Pungsi Lumbal (Lumbar Puncture)', 'description' => 'Pengambilan cairan serebrospinal (LCS) dari kanalis spinalis.'],
            ['name' => 'Pungsi Pleura (Thoracentesis)', 'description' => 'Pengambilan cairan dari rongga pleura.'],
            ['name' => 'Pungsi Asites (Paracentesis)', 'description' => 'Pengambilan cairan dari rongga perut (peritoneum).'],
            ['name' => 'Kumbah Lambung (Gastric Lavage)', 'description' => 'Tindakan mencuci atau membilas lambung untuk mengeluarkan racun atau darah.'],
            
            // Tindakan Diagnostik & Lainnya
            ['name' => 'EKG (Elektrokardiogram)', 'description' => 'Perekaman aktivitas kelistrikan jantung.'],
            ['name' => 'USG Dasar / FAST', 'description' => 'Pemeriksaan ultrasonografi dasar / Focused Assessment with Sonography for Trauma.'],
            ['name' => 'Nebulisasi', 'description' => 'Pemberian obat hirup (aerosol) untuk membuka saluran napas.'],
            ['name' => 'Suction Lendir (Airway Suctioning)', 'description' => 'Tindakan pengisapan lendir dari saluran pernapasan.'],
            
            // Kebidanan & Kandungan (Obgyn)
            ['name' => 'Persalinan Normal (APN)', 'description' => 'Pertolongan persalinan normal (Asuhan Persalinan Normal).'],
            ['name' => 'Kuretase', 'description' => 'Tindakan pembersihan jaringan dari dalam rahim.'],
        ];

        foreach ($activities as $activity) {
            \App\Models\MedicalActivity::create($activity);
        }
    }
}
