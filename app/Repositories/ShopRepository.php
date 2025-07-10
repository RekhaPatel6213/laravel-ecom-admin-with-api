<?php

namespace App\Repositories;

use App\Models\Shop;
use App\Models\Order;
use App\Contracts\ModalInterface;
use Illuminate\Support\Facades\DB;

class ShopRepository extends BaseRepository implements ModalInterface
{
    public function model()
    {
        return Shop::class;
    }
    public function getGraphNullShopOrders()
    {
        return Order::whereNull('shop_id')
            ->select(
                DB::raw('DATE_FORMAT(created_at, "%Y-%m") as month'),
                DB::raw('count(*) as count')
            )
            ->where('created_at', '>=', now()->subMonths(12)->startOfMonth())
            ->groupBy('month')
            ->orderBy('month')
            ->get();
    }
}
