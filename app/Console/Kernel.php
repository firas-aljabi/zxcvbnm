<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{

    protected $commands = [
        Commands\AttendanceDaily::class,
        Commands\AlertCron::class,
        Commands\GenerateSalaries::class,
    ];

    protected function schedule(Schedule $schedule)
    {
        $schedule->command('attendance:cron')->dailyAt('23:29');
        $schedule->command('alert:cron')->dailyAt('23:29');
        $schedule->command('generate:salaries')->monthlyOn(now()->startOfMonth()->format('j'), '00:00');
    }

    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}