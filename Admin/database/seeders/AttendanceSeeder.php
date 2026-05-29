<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Attendance;
use App\Models\User;
use Illuminate\Support\Carbon;

class AttendanceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ambil user pertama untuk test
        $users = User::take(5)->get();

        foreach ($users as $user) {
            // Buat data absensi untuk 30 hari terakhir
            for ($i = 0; $i < 30; $i++) {
                $date = Carbon::now()->subDays($i);
                
                // Skip weekends
                if ($date->isWeekend()) {
                    continue;
                }

                // Tentukan status absensi (acak)
                $randomStatus = rand(0, 100);
                $status = 'hadir'; // Default hadir (70%)
                
                if ($randomStatus < 10) {
                    $status = 'alpha'; // 10% alpha
                } elseif ($randomStatus < 15) {
                    $status = 'sakit'; // 5% sakit
                } elseif ($randomStatus < 20) {
                    $status = 'izin'; // 5% izin
                }

                Attendance::firstOrCreate(
                    [
                        'user_id' => $user->id,
                        'attendance_date' => $date->toDateString(),
                    ],
                    [
                        'status' => $status,
                        'keterangan' => $status === 'hadir' ? null : 'Data test seeder',
                    ]
                );
            }
        }
    }
}

