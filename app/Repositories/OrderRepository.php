<?php

namespace App\Repositories;

use App\Contracts\ModalInterface;
use App\Models\Order;
use App\Models\Meeting;
use App\Models\NoOrder;
use App\Models\OrderProduct;
use App\Models\Tada;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class OrderRepository extends BaseRepository implements ModalInterface
{
    public function model()
    {
        return Order::class;
    }

    public function getTodayProductiveCount()
    {
        return Order::whereDate('created_at', Carbon::today())->count();
    }

    public function getTodayUnproductiveCount()
    {
        return NoOrder::whereDate('created_at', Carbon::today())->count();
    }

    public function getTodayPrimaryOrderCount()
    {
        return Order::whereDate('created_at', Carbon::today())
            ->whereNull('shop_id')
            ->count();
    }

    public function getGraphOrdersCount()
    {
        return Order::select(
            DB::raw('DATE_FORMAT(created_at, "%Y-%m") as month'),
            DB::raw('count(*) as count')
        )
            ->where('created_at', '>=', now()->subMonths(12)->startOfMonth())
            ->groupBy('month')
            ->orderBy('month')
            ->get();
    }

    public function getProductiveUnproductiveData()
    {
        return Order::select(
            DB::raw('DATE(created_at) as date'),
            DB::raw('count(*) as productive_count'),
            DB::raw('"productive" as type')
        )
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->merge(
                NoOrder::select(
                    DB::raw('DATE(created_at) as date'),
                    DB::raw('count(*) as unproductive_count'),
                    DB::raw('"unproductive" as type')
                )
                    ->groupBy('date')
                    ->orderBy('date')
                    ->get()
            );
    }

    public function getMonthlyOrderCounts(?string $startDate = null, ?string $endDate = null, int $defaultMonths = 12)
    {
        $query = $this->model->select(
            DB::raw('DATE_FORMAT(created_at, "%Y-%m") as month'),
            DB::raw('count(*) as count')
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

    public function getNullShopOrderCounts(
        ?string $startDate = null,
        ?string $endDate = null,
        int $defaultMonths = 12,
        string $periodFormat = '%Y-%m'
    ) {
        $query = $this->model->whereNull('shop_id')
            ->select(
                DB::raw("DATE_FORMAT(created_at, '{$periodFormat}') as period"),
                DB::raw('COUNT(*) as count')
            );

        if ($startDate && $endDate) {
            $query->whereBetween('created_at', [$startDate, $endDate]);
        } else {
            $query->where('created_at', '>=', Carbon::now()->subMonths($defaultMonths)->startOfMonth());
        }

        return $query->groupBy('period')
            ->orderBy('period', 'asc')
            ->get();
    }

    
}
