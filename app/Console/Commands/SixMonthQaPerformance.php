<?php

namespace App\Console\Commands;

use App\Audit;
use App\SixMonthQaPerformance as AppSixMonthQaPerformance;
use Illuminate\Console\Command;

class SixMonthQaPerformance extends Command
{
    protected $signature = 'SixMonth:Performance';
    protected $description = 'Generate six-month QA performance data for auditors';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $months = [];
        $auditData = [];

        // Loop through the last 6 months
        // for ($i = 0; $i < 6; $i++) {
        for ($i = 5; $i >= 0; $i--) {
            $start_of_month = date("Y-m-01", strtotime(date('Y-m-01') . " -$i months"));
            $end_of_month = date("Y-m-t", strtotime($start_of_month));
            $months[] = $start_of_month;

            $monthlyData = Audit::whereDate('audit_date', '>=', $start_of_month)
                ->whereDate('audit_date', '<=', $end_of_month)
                ->groupBy('audited_by_id')
                ->selectRaw('audited_by_id, COUNT(*) as count')
                ->get()
                ->pluck('count', 'audited_by_id');

            foreach ($monthlyData as $auditor => $count) {
                $auditData[$auditor][$start_of_month] = $count;
            }
        }

        foreach ($auditData as $auditor => &$data) {
            foreach ($months as $month) {
                if (!isset($data[$month])) {
                    $data[$month] = 0;
                }
            }
            ksort($data);

            // Use updateOrCreate to handle updates or insertions


            $auditor_exist = AppSixMonthQaPerformance::where('auditor_id', $auditor)->first();
            if ($auditor_exist) {
                $auditor_exist->auditor_id = $auditor;
                $auditor_exist->month_1 = $data[$months[0]] ?? 0;
                $auditor_exist->month_2 = $data[$months[1]] ?? 0;
                $auditor_exist->month_3 = $data[$months[2]] ?? 0;
                $auditor_exist->month_4 = $data[$months[3]] ?? 0;
                $auditor_exist->month_5 = $data[$months[4]] ?? 0;
                $auditor_exist->month_6 = $data[$months[5]] ?? 0;
                $auditor_exist->save();
            } else {
                $month_data = new AppSixMonthQaPerformance();
                $month_data->auditor_id = $auditor;
                $month_data->month_1 = $data[$months[0]] ?? 0;
                $month_data->month_2 = $data[$months[1]] ?? 0;
                $month_data->month_3 = $data[$months[2]] ?? 0;
                $month_data->month_4 = $data[$months[3]] ?? 0;
                $month_data->month_5 = $data[$months[4]] ?? 0;
                $month_data->month_6 = $data[$months[5]] ?? 0;
                $month_data->save();
            }
        }
    }
}
