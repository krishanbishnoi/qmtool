<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Audit;
use App\AuditParameterResult;
use App\AuditResult;

use App\TmpAudit;
use App\TmpAuditParameterResult;
use App\TmpAuditResult;




class testPool extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:pool';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This Command is to test scheduler';

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

      echo "tst scheduler run";
        }

     
}
