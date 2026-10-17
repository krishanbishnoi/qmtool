<?php

namespace App\Console\Commands;

use App\User;
use DateTime;
use Illuminate\Console\Command;

class PasswordupdateChecker extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'password:updateChecker';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        
        $today = date('Y-m-d H:i:s');

        $users = User::where('status',1)->get();

        foreach( $users as $key => $value){

            $today_date = new DateTime($today);
            $last_password_date = new DateTime($value->password_change_date);

            $difference_days = $today_date->diff($last_password_date);


            if($difference_days->days > 90){
                $value->is_first_time_user = 1;
                $value->save();
            }
        }


    }
}
