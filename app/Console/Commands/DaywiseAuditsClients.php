<?php

namespace App\Console\Commands;

use App\Audit;
use App\Client;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class DaywiseAuditsClients extends Command
{
    protected $signature = 'auditor:auditCount';
    protected $description = 'Generate day-wise audit count report for auditors';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $auditors = Audit::join('users', 'audits.audited_by_id', '=', 'users.id')
            ->select('audits.audited_by_id', 'users.name', 'users.email', 'users.qd_id', 'users.parent_client')
            ->distinct()
            ->get();

        // $currentDate = Carbon::now();
        // $startOfMonth = $currentDate->copy()->startOfMonth();
        // $dateRange = CarbonPeriod::create($startOfMonth, $currentDate);

        $startOfMonth = Carbon::parse('2025-09-01');
        $currentDate = Carbon::parse('2025-09-30');
        $dateRange = CarbonPeriod::create($startOfMonth, $currentDate);

        $test = "<table style='border:1px solid black;'>
            <tr>
                <th style='border:1px solid black;text-align:center;background-color:#FFE4C4;'>Auditor Email</th>
                <th style='border:1px solid black;text-align:center;background-color:#FFE4C4;'>Client Name</th>
                <th style='border:1px solid black;text-align:center;background-color:#FFE4C4;'>QD ID</th>";

        $dateTotals = [];
        foreach ($dateRange as $date) {
            $dateFormat = $date->format('Y-m-d');
            $test .= "<th style='border:1px solid black;text-align:center;background-color:#FFE4C4;'>$dateFormat</th>";
            $dateTotals[$dateFormat] = 0;
        }

        $test .= "<th style='border:1px solid black;text-align:center;background-color:#FFE4C4;'>MTD</th></tr><tbody>";

        $mtdTotal = 0;

        foreach ($auditors as $auditor) {
            $auditorData = ['Auditor' => $auditor->email];
            $totalAuditor = 0;
            $auditorShouldBeIncluded = false;

            foreach ($dateRange as $date) {
                $count = DB::table('audits')
                    ->where('audited_by_id', $auditor->audited_by_id)
                    ->whereDate('audit_date', $date)
                    ->count();

                $totalAuditor += $count;
                $auditorData[$date->format('Y-m-d')] = $count;
                $dateTotals[$date->format('Y-m-d')] += $count;

                if ($count > 0) {
                    $auditorShouldBeIncluded = true;
                }
            }

            if ($auditorShouldBeIncluded) {
                $client = Client::find($auditor->parent_client);
                $clientName = $client ? $client->name : 'NA';

                $test .= "<tr>
                    <td style='border:1px solid black;text-align:center;'>{$auditor->email}</td>
                    <td style='border:1px solid black;text-align:center;'>{$clientName}</td>
                    <td style='border:1px solid black;text-align:center;'>{$auditor->qd_id}</td>";

                foreach ($dateRange as $date) {
                    $test .= "<td style='border:1px solid black;text-align:center;'>{$auditorData[$date->format('Y-m-d')]}</td>";
                }

                $test .= "<td style='border:1px solid black;text-align:center;'>$totalAuditor</td></tr>";
            }

            $mtdTotal += $totalAuditor;
        }

        $test .= "<tr><td style='border:1px solid black;text-align:center;background-color:#FFE4C4;' colspan='2'>Total</td>";
        foreach ($dateTotals as $total) {
            $test .= "<td style='border:1px solid black;text-align:center;background-color:#FFE4C4;'>$total</td>";
        }
        $test .= "<td style='border:1px solid black;text-align:center;background-color:#FFE4C4;'>$mtdTotal</td></tr>";

        $test .= "</tbody></table>";

        $file1 = "AuditorAuditCountBajajReport.xls";
        $xls1 = new \HtmlExcel();
        $xls1->addSheet("AuditorAuditCountBajajReport", $test);
        $ddddd1 = $xls1->buildFile();
        file_put_contents(public_path($file1), $ddddd1);
    }
}
