<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CompanySeeder extends Seeder
{
    public function run(): void
    {
        $addressId1 = DB::table('address')->insertGetId([
            'street' => 'Palárikova',
            'house_number' => '3',
            'city' => 'Nitra',
            'zip_code' => '94901',
            'country' => 'Slovensko',
        ]);

        $addressId2 = DB::table('address')->insertGetId([
            'street' => 'Novozámocká',
            'house_number' => '233',
            'city' => 'Nitra-Dolné Krškany',
            'zip_code' => '94905',
            'country' => 'Slovensko',
        ]);

        $addressId3 = DB::table('address')->insertGetId([
            'street' => 'Janka Alexyho ',
            'house_number' => '2954/1A',
            'city' => 'Bratislava',
            'zip_code' => '84101',
            'country' => 'Slovensko',
        ]);

        DB::table('companies')->insert([
            [
                'name' => 'UNIQA Group Service Center',
                'ico' => '34145311',
                'address_id' => $addressId1,
            ],
            [
                'name' => 'Muehlbauer Automation',
                'ico' => '51952491',
                'address_id' => $addressId2,
            ],
            [
                'name' => 'AMCEF',
                'ico' => '51026694',
                'address_id' => $addressId3,
            ],
        ]);
    }
}
