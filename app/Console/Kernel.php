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
        // $schedule->command('test:pool')->everyMinute();

        $schedule->command('callaudit:sendmail')->dailyAt('04:00');
        // $schedule->command('Batra:RawDump')->dailyAt('16:34');
        //  $schedule->command('rawDump:hotstar')->dailyAt('16:07'); // real excel
        $schedule->command('callaudit:notification')->dailyAt('02:30');
        $schedule->command('delete:purge')->weeklyOn(7, '23:00');
        $schedule->command('delete:audit_log')->daily();
        $schedule->command('incomplete:audit')->dailyAt('01:30');
        // $schedule->command('incomplete:audit')->dailyAt('11:23');
        $schedule->command('tmp:pool')->dailyAt('20:00');
        $schedule->command('client:report')->dailyAt('01:00');
        // $schedule->command('rawdump:report')->dailyAt('10:31');
        // $schedule->command('BFL:report')->dailyAt('09:30');
        // $schedule->command('quality:ScoresBFL')->dailyAt('10:10');      
        // $schedule->command('Feedback:Report')->dailyAt('12:12');
        // $schedule->command('Feedback:Report')->dailyAt('11:07');
        // $schedule->command('Feedback:Report')->dailyAt('01:41');
        $schedule->command('Feedback:Report')->dailyAt('09:53');
        $schedule->command('Feedback:Report')->dailyAt('10:43');
      
        // $schedule->command('Feedback:Report')->dailyAt('10:16');
        $schedule->command('feedback:reportoverall')->dailyAt('10:35');
        $schedule->command('feedback:reportoverall')->dailyAt('22:01');

        $schedule->command('feedback:report2')->dailyAt('09:50');
        $schedule->command('agentfeedback:report2')->dailyAt('07:00');
        // $schedule->command('feedback:report2')->dailyAt('16:04');
        // $schedule->command('lesstime:calls')->dailyAt('21:30');
        $schedule->command('HourlyReport:Report')->dailyAt('10:00');
        // $schedule->command('HourlyReport:Report')->dailyAt('12:00');
        $schedule->command('HourlyReport:Report')->dailyAt('14:00');
        // $schedule->command('HourlyReport:Report')->dailyAt('14:02');
        // $schedule->command('HourlyReport:Report')->dailyAt('16:03');        
        // $schedule->command('HourlyReport:Report')->dailyAt('11:00');        

        // $schedule->command('HourlyReport:Report')->dailyAt('16:00');        
        $schedule->command('HourlyReport:Report')->dailyAt('18:00');
        // $schedule->command('HourlyReport:Report')->dailyAt('20:00');
        $schedule->command('HourlyReport:Report')->dailyAt('22:00');
        $schedule->command('auditViewdate:update')->dailyAt('16:48');
        // $schedule->command('HourlyReport:Report')->dailyAt('17:38');
        $schedule->command('Bfl_combined:process')->weekly()->sundays()->at('23:00');
        // $schedule->command('Lending:Overall')->dailyAt('07:00');
        // $schedule->command('Tata:Productivity')->dailyAt('14:15');
        // $schedule->command('auditor:report')->dailyAt('12:03');  
        // $schedule->command('auditor:report')->dailyAt('18:00');  
        // $schedule->command('rawDump:hotstar')->dailyAt('13:15');  
        // $schedule->command('raw_dump_data:report')->dailyAt('14:55');

        // $schedule->command('raw_dump:report')->dailyAt('17:10');
        // $schedule->command('raw_dump_data:report')->dailyAt('11:21');// real excel
        // $schedule->command('raw_dump_data:report')->dailyAt('15:14');
        // $schedule->command('raw_dump_data:report')->dailyAt('11:48');
        $schedule->command('raw_dump_data:report')->dailyAt('23:00');
        $schedule->command('raw_dump_data:report')->dailyAt('13:41');
        // $schedule->command('raw_dump_data:report')->dailyAt('10:00');
        // $schedule->command('raw_dump_data:report')->dailyAt('10:39');

        // $schedule->command('raw_dump_data:report')->dailyAt('11:00');
        // $schedule->command('raw_dump_data:report')->dailyAt('12:07');
        // $schedule->command('raw_dump_data:report')->dailyAt('13:59');// real excel
        // $schedule->command('raw_dump_data:report')->dailyAt('17:15');// real excel
        // $schedule->command('raw_dump_data:report')->dailyAt('13:51');// real excel
        // $schedule->command('raw_dump_data:report')->dailyAt('12:19');// real excel
        // $schedule->command('ShubdhaMam:Report')->dailyAt('12:00');// real excel
        // $schedule->command('ShubdhaMam:Report')->dailyAt('17:00');// real excel
        // $schedule->command('ShubdhaMam:Report')->dailyAt('20:15');// real excel
        // $schedule->command('TATA:RawDump')->dailyAt('14:24');// real excel
       
        $schedule->command('SixMonth:Performance')->dailyAt('23:44');
        $schedule->command('password:updateChecker')->dailyAt('00:45');
        // $schedule->command('auditor:auditCount')->dailyAt('10:33');
        // $schedule->command('auditor:auditCount')->dailyAt('10:55');
        $schedule->command('dmi:productivity')->dailyAt('10:00');
        $schedule->command('dmi:productivity')->dailyAt('11:00');
        $schedule->command('dmi:productivity')->dailyAt('12:00');
        $schedule->command('dmi:productivity')->dailyAt('13:00');
        $schedule->command('dmi:productivity')->dailyAt('14:00');
        $schedule->command('dmi:productivity')->dailyAt('15:00');
        $schedule->command('dmi:productivity')->dailyAt('16:00');
        $schedule->command('dmi:productivity')->dailyAt('17:00');
        $schedule->command('dmi:productivity')->dailyAt('18:00');
        $schedule->command('dmi:productivity')->dailyAt('19:00');
        // $schedule->command('raw_dump:report')->dailyAt('11:50');
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
