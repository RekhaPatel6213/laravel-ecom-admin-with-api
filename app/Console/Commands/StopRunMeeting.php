<?php

namespace App\Console\Commands;

use App\Models\Meeting;
use App\Models\Route;
use Illuminate\Console\Command;

class StopRunMeeting extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:stop-run-meeting';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Stop Running Meeting & Route end';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $dateTime = date('Y-m-d H:i:s');

        \Log::info('StopRunMeeting Start => '.$dateTime);

        $allRunningMeeting = Meeting::whereNull(['end_time', 'end_latitude', 'end_longitude'])->update(['end_time' => $dateTime]);
        $allRunningRoute = Route::whereNull(['end_time', 'end_latitude', 'end_longitude'])->update(['end_time' => $dateTime]);

        \Log::info('StopRunMeeting End => '.$dateTime);
    }
}
