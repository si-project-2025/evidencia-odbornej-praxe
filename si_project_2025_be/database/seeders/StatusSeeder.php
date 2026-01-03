<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $statuses = [
            ['type' => 'Vytvorená'],
            ['type' => 'Potvrdená'],
            ['type' => 'Zamietnutá'],
            ['type' => 'Schválená'],
            ['type' => 'Neschválená'],
            ['type' => 'Obhájená'],
            ['type' => 'Neobhájená'],
        ];

        DB::table('status')->insert($statuses);
    }
}
