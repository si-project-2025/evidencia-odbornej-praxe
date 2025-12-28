<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        Schema::disableForeignKeyConstraints();


        DB::table('users')->truncate();
        DB::table('roles')->truncate();
        DB::table('address')->truncate();

        Schema::enableForeignKeyConstraints();

        DB::table('roles')->insert([
            ['role_id' => 1, 'name' => 'garant'],
            ['role_id' => 2, 'name' => 'student'],
            ['role_id' => 3, 'name' => 'firma'],
        ]);
    }
}
