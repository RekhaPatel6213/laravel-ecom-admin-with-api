<?php

namespace Database\Seeders;

use App\Models\CategoryType;
use Illuminate\Database\Seeder;

class CategoryTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categoryTypes = ['Small Packs', 'Big Packs', 'Institutional Packs'];

        foreach ($categoryTypes as $type) {
            CategoryType::create(['name' => $type]);
        }
    }
}
