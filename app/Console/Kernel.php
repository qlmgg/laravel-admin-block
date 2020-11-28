<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')
        //          ->hourly();

        //每分钟 执行一次队列
        $schedule->job(new \App\Jobs\Block\BatcheCron())->everyMinute();
        //每日积分退还 预约 没参与抢购的用户
        $schedule->job(new \App\Jobs\Block\GiveBackCreditCron())->dailyAt('23:30');
        //每日收益 每天 0 点执行一次任务
        $schedule->job(new \App\Jobs\Block\ContractCron())->dailyAt('01:30');
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
