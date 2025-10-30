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
        $roleId = DB::table('roles')->where('name', 'garant')->value('role_id');

        DB::table('users')->insert([
            'email' => 'garant@ukf.sk',
            'email_verified_at' => now(),
            'password' => Hash::make('password123'),
            'name' => 'Ján',
            'surname' => 'Novák',
            'alt_email' => 'jan.novak@gmail.com',
            'created_at' => now(),
            'role_id' => $roleId,
        ]);
    }
}
