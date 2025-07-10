<?php

namespace Database\Seeders;

use App\Models\OrderStatus;
use Illuminate\Database\Seeder;

class OrderStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $statusArray = ['Pending', 'Process', 'Complete', 'Cancelled', 'Failed'];

        foreach ($statusArray as $status) {
            OrderStatus::create(['order_status_name' => $status]);
        }
    }
}
