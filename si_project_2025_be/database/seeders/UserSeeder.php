<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $garantRoleId = DB::table('roles')->where('name', 'garant')->value('role_id');
        $companyRoleId = DB::table('roles')->where('name', 'firma')->value('role_id');

        DB::table('users')->insert([
            'email' => 'garant@ukf.sk',
            'email_verified_at' => now(),
            'password' => Hash::make('12345678'),
            'name' => 'Ján',
            'surname' => 'Novák',
            'alt_email' => 'jan.novak@gmail.com',
            'created_at' => now(),
            'role_id' => $garantRoleId,
        ]);

        $companyUserId = DB::table('users')->insertGetId([
            'email' => 'company@uniqua.sk',
            'email_verified_at' => now(),
            'password' => Hash::make('12345678'),
            'name' => 'UNIQA Group Service Center',
            'surname' => '',
            'created_at' => now(),
            'role_id' => $companyRoleId,
        ]);

        DB::table('companies')
            ->where('company_id', 1)
            ->update([
                'user_id' => $companyUserId,
            ]);
    }
}
