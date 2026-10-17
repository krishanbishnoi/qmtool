<?php

namespace App\Console\Commands;

use App\Audit;
use App\AuditResult;
use App\Client;
use App\Mail\DmiProductivity;
use App\User;
use App\Process;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DmiProductivityReport extends Command
{
    protected $signature = 'dmi:productivity';
    protected $description = 'Generate hourly productivity report per QA per process';

    public function handle()
    {
        $toDate = date("Y-m-d");
        $data = [];

        $clientList = [1, 11, 13, 24, 26, 27, 17, 22, 15];

        $timeSlots = [
            '9-10'  => ['09:00:00', '09:59:59'],
            '10-11' => ['10:00:00', '10:59:59'],
            '11-12' => ['11:00:00', '11:59:59'],
            '12-01'  => ['12:00:00', '12:59:59'],
            '01-02'  => ['13:00:00', '13:59:59'],
            '02-03'  => ['14:00:00', '14:59:59'],
            '03-04'   => ['15:00:00', '15:59:59'],
            '04-05'   => ['16:00:00', '16:59:59'],
            '05-06'   => ['17:00:00', '17:59:59'],
            '06-07'   => ['18:00:00', '18:59:59'],
        ];

        // Get unique QAs who did audits today
        $qaIds = Audit::whereIn('client_id', $clientList)
            ->whereDate('audit_date', $toDate)
            ->pluck('audited_by_id')
            ->unique()
            ->toArray();

        $qaIds = User::whereIn('id', $qaIds)
            ->where('status', 1)
            ->whereHas('roles', fn($q) => $q->where('name', 'qa'))
            ->pluck('id')
            ->toArray();

        if (empty($qaIds)) {
            $this->info("No audits found for today.");
            return;
        }

        foreach ($qaIds as $qaId) {
            $user = User::find($qaId);
            if (!$user) continue;

            // Get all audits of this QA
            $audits = Audit::whereIn('client_id', $clientList)
                ->where('audited_by_id', $qaId)
                ->whereDate('audit_date', $toDate)
                ->get();

            if ($audits->isEmpty()) continue;

            // Group audits by client for hourly breakdown
            $groupedAudits = $audits->groupBy('client_id');

            // ----> Calculate overall fatal score per QA here
            $auditIds = $audits->pluck('id')->toArray();
            $audit_result_score_overall = 0;

            foreach ($auditIds as $auditId) {
                $scores = AuditResult::where('audit_id', $auditId)->get();
                $is_critical = $scores->contains('is_critical', 1);
                $withFatalScore = !$is_critical ? $scores->sum('score') : 0;
                $audit_result_score_overall += $withFatalScore;
            }

            $audit_result_scorable_overall = AuditResult::whereIn('audit_id', $auditIds)
                ->select(DB::raw('SUM(after_audit_weight) as scorable'))
                ->value('scorable') ?? 0;

            $fatal_score_overall = $audit_result_scorable_overall > 0
                ? ($audit_result_score_overall / $audit_result_scorable_overall) * 100
                : 0;

            // ----> Hourly breakdown + client split
            foreach ($groupedAudits as $clientId => $rows) {
                $clientName = Client::find($clientId)->name ?? 'NA';
                $clienttarget = Client::find($clientId)->targets ?? 40;

                $hourCounts = [];
                $total = 0;

                foreach ($timeSlots as $label => [$start, $end]) {
                    $count = $rows->filter(function ($audit) use ($start, $end) {
                        $time = Carbon::parse($audit->created_at)->format('H:i:s');
                        return $time >= $start && $time <= $end;
                    })->count();

                    $hourCounts[$label] = $count;
                    $total += $count;
                }

                $data[] = [
                    'qa_name' => $user->name,
                    'client_name' => $clientName,
                    'target' => $clienttarget,
                    'hours' => $hourCounts,
                    'total' => $total,
                    'fatal_score_overall' => round($fatal_score_overall, 2), // 👈 added here
                ];
            }
        }

        // ----> Totals Row
        $totalRow = [
            'qa_name' => 'Total',
            'client_name' => '',
            'target' => array_sum(array_column($data, 'target')),
            'hours' => [],
            'total' => array_sum(array_column($data, 'total')),
            'fatal_score_overall' => '', // Not meaningful for total row
        ];

        foreach (array_keys($timeSlots) as $label) {
            $totalRow['hours'][$label] = collect($data)->sum(function ($row) use ($label) {
                return $row['hours'][$label] ?? 0;
            });
        }

        $data[] = $totalRow;

        // ----> Send Email
        $toEmails = ["sonu.singh@qdegrees.org", "dipti.bellani@qdegrees.org", "vishal.gahlot@qdegrees.org", "pawan.saini@qdegrees.org", "sumitra.luhar@qdegrees.org", "vaibhav.chaudhary@qdegrees.org", "sushma.mondal@qdegrees.org", "deepa.thapa@qdegrees.org", "rachna.mendiratta@qdegrees.org", "prakash.dey@qdegrees.org", "muhammed.musammil@qdegrees.org", "Vishnumaya@qdegrees.com","vivek.garg@qdegrees.com"];
        $ccEmails = ["Jasim.Ali@qdegrees.com", "amit.kumar3@qdegrees.com", "gourav.goutam@qdegrees.com","dipali.barman@qdegrees.org","himanshu.mathur@qdegrees.org","sachin.kumar@qdegrees.org","adish.sah@qdegrees.org"];

        Mail::to($toEmails)->cc($ccEmails)->send(new DmiProductivity($data));

        $this->info("Hourly + Fatal Score report sent successfully for {$toDate}");
    }
}
