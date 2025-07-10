<?php

namespace Database\Seeders;

use App\Models\VariantType;
use App\Models\VariantValue;
use Illuminate\Database\Seeder;

class ProductVariantSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $variants = ['Pack', 'Pouch'];
        foreach ($variants as $variant) {
            VariantType::create(['name' => $variant]);
        }

        $values = ['2 KG', '1 Kg', '40 Grams', '160 Grams'];
        foreach ($values as $value) {
            VariantValue::create(['name' => $value]);
        }
    }
}
