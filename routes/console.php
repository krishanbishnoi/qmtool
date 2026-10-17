<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of your Closure based console
| commands. Each Closure is bound to a command instance allowing a
| simple approach to interacting with each command's IO methods.
|
*/

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->describe('Display an inspiring quote');

/*
|--------------------------------------------------------------------------
| Application Schedule
|--------------------------------------------------------------------------
|
| Migrated from app/Console/Kernel.php for Laravel 11 compatibility.
|
*/

Schedule::command('callaudit:sendmail')->dailyAt('04:00');
Schedule::command('callaudit:notification')->dailyAt('02:30');
Schedule::command('delete:purge')->weeklyOn(7, '23:00');
Schedule::command('delete:audit_log')->daily();
Schedule::command('incomplete:audit')->dailyAt('01:30');
Schedule::command('tmp:pool')->dailyAt('20:00');
Schedule::command('client:report')->dailyAt('01:00');
Schedule::command('Feedback:Report')->dailyAt('09:53');
Schedule::command('Feedback:Report')->dailyAt('10:48');
Schedule::command('feedback:reportoverall')->dailyAt('10:35');
Schedule::command('feedback:reportoverall')->dailyAt('22:01');
Schedule::command('feedback:report2')->dailyAt('19:37');
Schedule::command('agentfeedback:report2')->dailyAt('07:00');
Schedule::command('HourlyReport:Report')->dailyAt('10:00');
Schedule::command('HourlyReport:Report')->dailyAt('14:00');
Schedule::command('HourlyReport:Report')->dailyAt('18:00');
Schedule::command('HourlyReport:Report')->dailyAt('22:00');
Schedule::command('auditViewdate:update')->dailyAt('16:48');
Schedule::command('Bfl_combined:process')->weekly()->sundays()->at('23:00');
Schedule::command('raw_dump_data:report')->dailyAt('23:00');
Schedule::command('raw_dump_data:report')->dailyAt('13:41');
Schedule::command('SixMonth:Performance')->dailyAt('23:44');
Schedule::command('password:updateChecker')->dailyAt('00:45');
Schedule::command('dmi:productivity')->dailyAt('10:00');
Schedule::command('dmi:productivity')->dailyAt('11:00');
Schedule::command('dmi:productivity')->dailyAt('12:00');
Schedule::command('dmi:productivity')->dailyAt('13:00');
Schedule::command('dmi:productivity')->dailyAt('14:00');
Schedule::command('dmi:productivity')->dailyAt('15:00');
Schedule::command('dmi:productivity')->dailyAt('16:00');
Schedule::command('dmi:productivity')->dailyAt('17:00');
Schedule::command('dmi:productivity')->dailyAt('18:00');
Schedule::command('dmi:productivity')->dailyAt('19:00');
