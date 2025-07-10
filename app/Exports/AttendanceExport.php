<?php

namespace App\Exports;

use App\Models\User;
use Illuminate\Support\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Events\BeforeSheet;

class AttendanceExport implements FromCollection, ShouldAutoSize, WithEvents, WithHeadings
{
    protected $user_id;

    protected $year;

    protected $month;

    public function __construct($user_id, $year, $month)
    {
        $this->user_id = $user_id;
        $this->year = $year;
        $this->month = $month;

        $this->dates = collect(range(1, Carbon::create($year, $month, 1)->daysInMonth))->map(function ($day) use ($year, $month) {
            return Carbon::create($year, $month, $day)->format('D j');
        });
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $startDate = Carbon::createFromFormat('Y-m', $this->year.'-'.$this->month)->startOfMonth();
        $endDate = Carbon::createFromFormat('Y-m', $this->year.'-'.$this->month)->endOfMonth();

        $attendance = User::select('id', 'firstname', 'lastname')->with(['route' => function ($query) use ($startDate, $endDate) {
            $query->whereBetween('created_at', [$startDate, $endDate]);
        }])
            ->where('role_id', '!=', config('constants.ADMIN_ROLE_ID'));

        if ($this->user_id) {
            $attendance->where('id', $this->user_id);
        }

        $attendance = $attendance->get();

        $result = [];

        foreach ($attendance as $index => $user) {
            $attendanceDate = [];

            $row = [
                'SR. No.' => $index + 1,
                'Sales Person Name' => $user->firstname.' '.$user->lastname,
            ];

            foreach ($user->route as $rvalue) {
                $attendanceDate[] = \Carbon\Carbon::parse($rvalue['created_at'])->format('Y-m-d');
            }

            for ($day = 1; $day <= Carbon::create($this->year, $this->month)->daysInMonth; $day++) {
                $currentDate = Carbon::create($this->year, $this->month, $day);
                $formattedDate = $currentDate->format('Y-m-d');

                if ($currentDate->isSunday()) {
                    $row[$day] = 'H';
                } elseif (in_array($formattedDate, $attendanceDate)) {
                    $row[$day] = 'P';
                } else {
                    $row[$day] = 'A';
                }
            }

            $result[] = $row;
        }

        return collect($result);
    }

    public function headings(): array
    {
        $monthYearHeader = ['Month - Year: '.Carbon::create($this->year, $this->month)->format('F Y')];

        $columnHeadings = array_merge(
            ['SR. No.', 'Sales Person Name'],
            $this->dates->toArray()
        );

        return [$monthYearHeader, $columnHeadings];
    }

    public function registerEvents(): array
    {
        return [
            BeforeSheet::class => function (BeforeSheet $event) {
                $sheet = $event->sheet->getDelegate();

                $sheet->getStyle('A1')->getFont()->setBold(true);
                $sheet->getStyle('A1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            },
        ];
    }
}
