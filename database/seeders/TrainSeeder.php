<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TrainSeeder extends Seeder
{
    public function run(): void
    {
        // Insert 1 kereta
        $trainId = DB::table('train_config')->insertGetId([
            'name' => 'KA Argo Bromo',
            'description' => 'Kereta eksekutif cepat Surabaya - Jakarta',
            'total_gerbong' => 2,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Insert 2 gerbong
        $car1Id = DB::table('train_cars')->insertGetId([
            'train_id' => $trainId,
            'car_number' => 1,
            'car_type' => 'Eksekutif',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $car2Id = DB::table('train_cars')->insertGetId([
            'train_id' => $trainId,
            'car_number' => 2,
            'car_type' => 'Ekonomi',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Insert 2 devices
        DB::table('devices')->insert([
            [
                'name' => 'Sensor Suhu & Kelembaban',
                'serial_number' => 'DEV-001',
                'status' => 'online',
                'topic' => 'train/argo-bromo/car1/suhu-humidity',
                'car_id' => $car1Id,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Noise Sensor',
                'serial_number' => 'DEV-002',
                'status' => 'offline',
                'topic' => 'train/argo-bromo/car2/noise',
                'car_id' => $car2Id,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
