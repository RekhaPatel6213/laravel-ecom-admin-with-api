<?php

namespace Database\Seeders;

use App\Models\MeetingType;
use Illuminate\Database\Seeder;

class MeetingTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $types = ['Average', 'Hot', 'Cold'];
        foreach ($types as $type) {
            MeetingType::create(['name' => $type]);
        }
    }
}
