<?php

namespace App\Repositories;

use App\Contracts\ModalInterface;
use App\Models\Tada;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class TadaRepository extends BaseRepository implements ModalInterface
{
    public function model()
    {
        return Tada::class;
    }

    public function getTodayTadaAmountSum()
    {
        return Tada::whereDate('created_at', Carbon::today())->sum('amount');
    }


    public function getGraphTadaAmount()
    {
        return Tada::select(
            DB::raw('DATE_FORMAT(created_at, "%Y-%m") as month'),
            DB::raw('SUM(amount) as total_amount')
        )
            ->where('created_at', '>=', now()->subMonths(12)->startOfMonth())
            ->groupBy('month')
            ->orderBy('month')
            ->get();
    }

    public function getMonthlyAmountSummary(
        ?string $startDate = null,
        ?string $endDate = null,
        int $defaultMonths = 12
    ) {
        $query = $this->model->select(
            DB::raw('DATE_FORMAT(created_at, "%Y-%m") as month'),
            DB::raw('SUM(amount) as total_amount')
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
