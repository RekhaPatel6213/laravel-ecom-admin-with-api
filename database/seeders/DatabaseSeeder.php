<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();
        $this->call(RoleSeeder::class);
        $this->call(DesignationSeeder::class);
        $this->call(ZoneSeeder::class);
        $this->call(CountrySeeder::class);
        $this->call(ProductVariantSeeder::class);
        $this->call(OrderStatusSeeder::class);
        $this->call(TadaTypeSeeder::class);

        User::factory()->create([
            'firstname' => 'Test',
            'lastname' => 'User',
            'email' => 'test@example.com',
            'designation_id' => 1,
            'role_id' => 1,
        ]);
    }
}
