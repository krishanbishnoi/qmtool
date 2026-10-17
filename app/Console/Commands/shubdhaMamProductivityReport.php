<?php

namespace App\Console\Commands;

use App\Audit;
use App\Mail\ShubhDhaMamReport;
use App\Process;
use App\QaTarget;
use App\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class shubdhaMamProductivityReport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ShubdhaMam:Report';

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
        $toDate = date("Y-m-d");
        $data = [];

        $userEmailMapping = [
            'Deepa Thapa' => [
                'DeepaAIRAN@qdegrees.com', 
                'deepaidfccst@qdegrees.com', 
                'Deepa.gtpl@qdegrees.com'
            ],
            'Sushma Mondal' => [
                'sushma.Drbatras@qdegrees.com', 
                'sushma.mandol1@qdegrees.com'
            ],
            'Rachna Mendiratta' => [
                'rachna.mendiratta@qdegrees.org'
            ],
            'Dimpal Patel' => [
                'dimpal.patel@qdegrees.com'
            ],
            'Dipti Bellani' => [
                'dipti.bellani@qdegrees.org'
            ],
            'Prakash Dey' => [
                'Prakash.dey@qdegrees.org', 
                'Prakashidfc@qdegrees.com'
            ],
            'Ananda' =>[
                'ananda@qdegrees.org',
                'ananda.idfc@qdegrees.org'
            ],
            'Pallavee Laddha' =>[
                'laddha.pallavee@qdegrees.org'
            ]
        ];

       
        foreach ($userEmailMapping as $userName => $emails) {
            foreach ($emails as $email) {
                $user = User::where('email', $email)->first();

                if ($user) {
                    $auditCount = Audit::whereDate('audit_date', $toDate)
                        ->where('audited_by_id', $user->id)
                        ->count();

                    // Get all process names
                    $process = json_decode($user->assigned_process_ids);
                    $processNames = [];

                    if (!empty($process)) {
                        foreach ($process as $processId) {
                            $processObj = Process::find($processId);
                            if ($processObj) {
                                $processNames[] = $processObj->name;
                            }
                        }
                    }

                    $data[$userName][] = [
                        'email' => $email,
                        'auditCount' => $auditCount,
                        'processNames' => $processNames,
                    ];
                }
            }
        }
        $to_emails = ["Jasim.Ali@qdegrees.com","amit.kumar3@qdegrees.com"];
        Mail::to($to_emails)->send(new ShubhDhaMamReport($data)); 
        
    }
}
