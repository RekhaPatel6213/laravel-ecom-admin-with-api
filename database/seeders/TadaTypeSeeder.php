<?php

namespace Database\Seeders;

use App\Models\TadaType;
use Illuminate\Database\Seeder;

class TadaTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tadaTypes = [
            'Bus' => config('constants.PHOTO'),
            'Train' => config('constants.PHOTO'),
            'Bike' => config('constants.KM'),
            'Car' => config('constants.KM'),
        ];

        if ($tadaTypes) {
            foreach ($tadaTypes as $name => $type) {
                TadaType::create(['name' => $name, 'type' => $type]);
            }
        }
    }
}
