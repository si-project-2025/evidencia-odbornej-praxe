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
        // 1. Dočasne vypneme kontrolu cudzích kľúčov
        Schema::disableForeignKeyConstraints();

        // 2. Vymažeme dáta z tabuliek v opačnom poradí (najprv deti, potom rodičia)
        // Toto je najbezpečnejší prístup. Vymažeme aj users, aby sme mali istotu.
        DB::table('users')->truncate();
        DB::table('roles')->truncate();
        // Ak máš ďalšie prepojené tabuľky, pridaj ich sem (napr. addresses)
        DB::table('address')->truncate();


        // 3. Znovu zapneme kontrolu cudzích kľúčov
        Schema::enableForeignKeyConstraints();

        // 4. Až teraz vložíme nové, čisté dáta pre roly
        DB::table('roles')->insert([
            ['role_id' => 1, 'name' => 'garant'],
            ['role_id' => 2, 'name' => 'student'],
        ]);
    }
}
