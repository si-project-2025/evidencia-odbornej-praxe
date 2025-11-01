<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CompanySeeder extends Seeder
{
    public function run(): void
    {
        $addressId1 = DB::table('address')->insertGetId([
            'street' => 'Hlavná',
            'house_number' => '10',
            'city' => 'Nitra',
            'zip_code' => '81101',
            'country' => 'Slovensko',
        ]);

        $addressId2 = DB::table('address')->insertGetId([
            'street' => 'Prievozská',
            'house_number' => '7',
            'city' => 'Bratislava',
            'zip_code' => '82109',
            'country' => 'Slovensko',
        ]);

        DB::table('companies')->insert([
            [
                'company_id' => 1,
                'name' => 'Uniqua',
                'ico' => 12345678,
                'address_id' => $addressId1,
            ],
            [
                'company_id' => 2,
                'name' => 'Muehlbauer',
                'ico' => 87654321,
                'address_id' => $addressId2,
            ],
        ]);
    }
}
