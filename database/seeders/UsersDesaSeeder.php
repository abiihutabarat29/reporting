<?php

namespace Database\Seeders;

use App\Models\UserDesa;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UsersDesaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        UserDesa::create([
            'kecamatan_id'    => 1,
            'desa_id'    => 1,
            'name'    => "Administrator Desa",
            'email'    => 'admindesa@gmail.com',
            'nohp'    => '123456789102',
            'password'    => bcrypt('admin123'),
            'role' => "3",
            'foto' => "",
        ]);
    }
}
