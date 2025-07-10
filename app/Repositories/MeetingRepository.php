<?php

namespace App\Repositories;

use App\Contracts\ModalInterface;
use App\Models\Meeting;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class MeetingRepository extends BaseRepository implements ModalInterface
{
    public function model()
    {
        return Meeting::class;
    }

    public function getGraphMeetingCount()
    {
        return Meeting::select(
            DB::raw('DATE_FORMAT(meeting_date, "%Y-%m") as month'),
            DB::raw('count(*) as count')
        )
            ->where('meeting_date', '>=', now()->subMonths(12)->startOfMonth())
            ->groupBy('month')
            ->orderBy('month')
            ->get();
    }

    public function getTodayMeetingCount()
    {
        return Meeting::whereDate('meeting_date', Carbon::today())->count();
    }

    public function getMonthlyMeetingCounts(?string $startDate = null, ?string $endDate = null)
    {
        $query = $this->model->select(
            DB::raw('DATE_FORMAT(meeting_date, "%Y-%m") as month'),
            DB::raw('count(*) as count')
        );

        if ($startDate && $endDate) {
            $query->whereBetween('meeting_date', [$startDate, $endDate]);
        } else {
            $query->where('meeting_date', '>=', Carbon::now()->subMonths(12)->startOfMonth());
        }

        return $query->groupBy('month')
            ->orderBy('month')
            ->get();
    }
}
