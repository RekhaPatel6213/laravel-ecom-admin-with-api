<?php

namespace Database\Seeders;

use App\Models\Zone;
use Illuminate\Database\Seeder;

class ZoneSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $zones = ['East', 'West', 'North', 'South'];

        if ($zones) {
            foreach ($zones as $key => $zone) {
                Zone::create(['name' => $zone, 'sort_order' => $key + 1]);
            }
        }
    }
}
