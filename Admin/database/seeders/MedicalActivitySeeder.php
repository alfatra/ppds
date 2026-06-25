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
            ['name' => 'Menyuntikkan Injeksi', 'description' => 'Tindakan menyuntikkan obat secara intravena, intramuskular, atau subkutan.'],
            ['name' => 'Memasang Infus', 'description' => 'Tindakan pemasangan akses intravena untuk pemberian cairan atau obat.'],
            ['name' => 'Hecting / Jahit Luka', 'description' => 'Tindakan menjahit luka robek atau luka pasca operasi.'],
            ['name' => 'Aff Hecting / Angkat Jahitan', 'description' => 'Tindakan mengangkat benang jahitan pada luka yang sudah mengering.'],
            ['name' => 'Pemasangan Kateter', 'description' => 'Tindakan memasukkan selang kateter ke dalam kandung kemih.'],
            ['name' => 'Pemasangan NGT', 'description' => 'Tindakan memasang selang Nasogastric Tube.'],
            ['name' => 'Perawatan Luka', 'description' => 'Tindakan membersihkan dan mengganti balutan luka.'],
        ];

        foreach ($activities as $activity) {
            \App\Models\MedicalActivity::create($activity);
        }
    }
}
