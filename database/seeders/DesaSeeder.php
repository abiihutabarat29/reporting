<?php

namespace Database\Seeders;

use App\Models\Desa;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DesaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $csvFile = fopen(public_path('data/desa.csv'), 'r');

        $firstline = true;
        while (($data = fgetcsv($csvFile, 2000, ';')) !== false) {
            if (!$firstline) {
                Desa::create([
                    'kecamatan_id' => $data['0'],
                    'kode' => $data['1'],
                    'name' => $data['2'],
                ]);
            }
            $firstline = false;
        }

        fclose($csvFile);
    }
}
