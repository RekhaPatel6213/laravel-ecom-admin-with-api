<?php

namespace Database\Seeders;

use App\Models\Designation;
use Illuminate\Database\Seeder;

class DesignationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $designations = ['NSM', 'ZSM', 'RSM', 'ASM', 'SO', 'RO'];

        if ($designations) {
            foreach ($designations as $designation) {
                Designation::create(['name' => $designation]);
            }
        }
    }
}
