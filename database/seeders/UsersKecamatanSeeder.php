<?php

namespace Database\Seeders;

use App\Models\UserKecamatan;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UsersKecamatanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        UserKecamatan::create([
            'kecamatan_id'    => 1,
            'name'    => "Administrator Kecamatan",
            'email'    => 'adminkecamatan@gmail.com',
            'nohp'    => '123456789102',
            'password'    => bcrypt('admin123'),
            'role' => "2",
            'foto' => "",
        ]);
    }
}
