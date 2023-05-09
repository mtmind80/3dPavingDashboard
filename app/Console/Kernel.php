<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected $commands = [
        //
    ];

    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }

    protected function schedule(Schedule $schedule)
    {
        $schedule->call(function (){
            //

        })
        ->hourlyAt('10')
        ->timezone(session()->get('timezone', 'America/New_York'))
        ->when(function () {
            return env('APP_ENV') == 'production';
        });

    }

}
