<?php

namespace App\Console\Commands;

use App\Audit;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;


class AuditViewDate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'auditViewdate:update';

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

        DB::table('audits')
        ->where('client_id', 23)
        ->whereNull('audit_view_date')
        ->whereDate('audit_date', '>=', '2025-08-01')
        ->update([
            'audit_view_date' => DB::raw('DATE_ADD(audit_date, INTERVAL 1 DAY)')
        ]);

        $this->info('Audit view dates updated successfully!');
        
    }
}
