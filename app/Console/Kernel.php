<?php

namespace App\Console;

use App\Models\Proposal;
use App\Notifications\OldProposalNotification;
use Exception;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Log;
use Str;

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
        // Notify old proposals (over 60 days old)
        $schedule->call(function (){
            $total = 0;
            $now = now(session()->get('timezone', 'America/New_York'));

            Proposal::where('on_alert', false)
                ->whereDate('created_at', '<', $now->copy()->subDays(60)->toDateString())
                ->with(['contact'])
                ->chunk(500, function ($proposals) use (& $total){
                    foreach ($proposals as $proposal) {
                        try {
                            $proposal->on_alert = true;
                            $proposal->alert_reason = 'Proposal Too Old';
                            $proposal->save();

                            $proposal->contact->notify(new OldProposalNotification($proposal));

                            $total++;
                        } catch (Exception $e) {
                            Log::error(env('APP_NAME').'. Error while sending "Proposal Too Old" notification. ' . $e->getMessage());
                        }
                    }
                });

            if ($total > 0) {
                Log::info(env('APP_NAME').'. Sent '.$total.' "Proposal Too Old" '.Str::plural('notification', $total).'.');
            }
        })
        ->dailyAt('01:00')
        ->timezone(session()->get('timezone', 'America/New_York'))
        ->when(function () {
            return false;     // change this to true to activate
            // or to:
            // return app()->environment() === 'production'; for running only in PRD
        });

    }

}
