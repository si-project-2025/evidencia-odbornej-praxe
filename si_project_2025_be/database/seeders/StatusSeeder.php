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
            'Vytvorená',
            'Potvrdená',
            'Zamietnutá',
            'Schválená',
            'Obhájená',
            'Neschválená',
            'Neobhájená'
        ];

        foreach ($statuses as $index => $type) {
            DB::table('status')->insert([
                'status_id' => $index + 1,
                'type' => $type,
            ]);
        }
    }
}
