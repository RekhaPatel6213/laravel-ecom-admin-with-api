<?php

namespace App\Repositories;

use App\Models\Product;
use App\Models\OrderProduct;
use App\Contracts\ModalInterface;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ProductRepository extends BaseRepository implements ModalInterface
{
    public function model()
    {
        return Product::class;
    }

    public function getGraphProductMrp()
    {
        return OrderProduct::select(
            DB::raw('DATE_FORMAT(created_at, "%Y-%m") as month'),
            DB::raw('SUM(product_mrp) as total_mrp')
        )
            ->where('created_at', '>=', now()->subMonths(12)->startOfMonth())
            ->groupBy('month')
            ->orderBy('month')
            ->get();
    }

    public function getMonthlyMrpSummary(?string $startDate = null, ?string $endDate = null, int $defaultMonths = 12)
    {
        $query = OrderProduct::select(
            DB::raw('DATE_FORMAT(created_at, "%Y-%m") as month'),
            DB::raw('SUM(product_mrp) as total_mrp')
        );

        if ($startDate && $endDate) {
            $query->whereBetween('created_at', [$startDate, $endDate]);
        } else {
            $query->where('created_at', '>=', Carbon::now()->subMonths($defaultMonths)->startOfMonth());
        }

        return $query->groupBy('month')
            ->orderBy('month')
            ->get();
    }
}
