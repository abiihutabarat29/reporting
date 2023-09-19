<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'kecamatan_id'    => null,
                'desa_id'    => null,
                'name'    => "Administrator",
                'email'    => 'admin@gmail.com',
                'nohp'    => null,
                'password'    => bcrypt('admin123'),
                'role' => "1",
                'foto' => "",
            ],
            [
                'kecamatan_id'    => 1,
                'desa_id'    => null,
                'name'    => "Administrator Kecamatan",
                'email'    => 'adminkecamatan@gmail.com',
                'nohp'    => '123456789102',
                'password'    => bcrypt('admin123'),
                'role' => "2",
                'foto' => "",
            ],
            [
                'kecamatan_id'    => 1,
                'desa_id'    => 1,
                'name'    => "Administrator Desa",
                'email'    => 'admindesa@gmail.com',
                'nohp'    => '123456789102',
                'password'    => bcrypt('admin123'),
                'role' => "3",
                'foto' => "",
            ]
        ];

        User::insert($data);
    }
}
