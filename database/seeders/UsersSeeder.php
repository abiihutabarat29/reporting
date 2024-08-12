<?php

namespace Database\Seeders;

use App\Models\ProfilUser;
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
        $users = [
            [
                'kecamatan_id' => null,
                'desa_id'      => null,
                'name'         => "Administrator",
                'email'        => 'admin@gmail.com',
                'nohp'         => null,
                'password'     => bcrypt('admin123'),
                'role'         => "1",
                'foto'         => "",
            ],
        ];

        foreach ($users as $user) {
            $createdUser = User::create($user);
            $profil = [
                [
                    'user_id'       => $createdUser->id,
                    'nama_pkk'      => null,
                    'nohp_kantor'   => null,
                    'alamat_kantor' => null,
                    'banner'        => null,
                ],
            ];

            ProfilUser::insert($profil);
        }
    }
}
