<?php

namespace App\Console;

use App\Models\Proposal;
use App\Models\ProposalNote;
use App\Notifications\OldProposalNotification;
use App\Notifications\ProposalNoteNotification;
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
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }

    protected function schedule(Schedule $schedule)
    {
        // Send note reminder
        $schedule->call(function () {
            $total = 0;
            ProposalNote::whereNotNull('reminder_date')
                ->where('remindersent', false)
                ->whereDate('reminder_date', now(config('app.timezone'))->toDateString())
                ->with(['proposal' => function ($q) {
                    $q->with(['salesPerson']);
                }])
                ->chunk(500, function ($proposalNotes) use (& $total) {
                    foreach ($proposalNotes as $proposalNote) {
                        try {
                            $proposalNote->remindersent = true;
                            $proposalNote->save();

                            $proposalNote->proposal->salesPerson->notify(new ProposalNoteNotification($proposalNote));

                            $total++;
                        } catch (Exception $e) {
                            Log::error(env('APP_NAME') . '. Error while sending proposal note reminder. ' . $e->getMessage());
                        }
                    }
                });

            if ($total > 0) {
                Log::info(env('APP_NAME') . '. Sent ' . $total . ' proposal note ' . Str::plural('reminder', $total) . '.');
            }
        })
            ->dailyAt('01:00')
            ->timezone(config('app.timezone'))
            ->when(function () {
                return true;     // change this to true to activate
                // or to:
                // return app()->environment() === 'production'; for running only in PRD
            });

        // Notify old proposals (over 60 days old)
        $schedule->call(function () {
            $total = 0;

            Proposal::whereDate('created_at', now(config('app.timezone'))->subDays(60)->toDateString())
                ->with(['salesPerson'])
                ->chunk(500, function ($proposals) use (& $total) {
                    foreach ($proposals as $proposal) {
                        try {
                            $proposal->on_alert = true;
                            $proposal->alert_reason = 'Proposal Too Old';
                            $proposal->save();

                            $proposal->salesPerson->notify(new OldProposalNotification($proposal));

                            $total++;
                        } catch (Exception $e) {
                            Log::error(env('APP_NAME') . '. Error while sending "Proposal Too Old" notification. ' . $e->getMessage());
                        }
                    }
                });

            if ($total > 0) {
                Log::info(env('APP_NAME') . '. Sent ' . $total . ' "Proposal Too Old" ' . Str::plural('notification', $total) . '.');
            }
        })
            ->dailyAt('01:30')
            ->timezone(config('app.timezone'))
            ->when(function () {
                return true;     // change this to true to activate
                // or to:
                // return app()->environment() === 'production'; for running only in PRD
            });


        $schedule->call(function () {
            Log::info(env('APP_NAME') . '. Check Cron ');
            })->everyFiveMinutes()
                ->timezone(config('app.timezone'))
                ->when(function () {
                    return false;     // change this to true to activate
                    // or to:
                    // return app()->environment() === 'production'; for running only in PRD
                });

    }



}
