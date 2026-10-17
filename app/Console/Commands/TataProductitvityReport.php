<?php

namespace App\Console\Commands;

use App\Audit;
use App\Mail\tata_productivity;
use App\Process;
use App\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style;
use Carbon\Carbon;

class TataProductitvityReport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'Tata:Productivity';

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



    private function secondsToHoursMinutes($seconds)
    {
        $hours = floor($seconds / 3600);
        $minutes = floor(($seconds % 3600) / 60);
        return [
            'hours'   => $hours,
            'minutes' => $minutes,
        ];
    }


    /**
     * Calculate percentage with validation.
     *
     * @param int|null $seconds
     * @param int $totalPossibleSeconds
     * @return string
     */
    private function calculatePercentage($seconds, $totalPossibleSeconds)
    {
        if ($seconds === null || $totalPossibleSeconds === 0) {
            return '0%';
        }
        $percentage = ($seconds / $totalPossibleSeconds) * 100;
        return round($percentage, 2) . '%';
    }

    /**
     * Assign ranks based on sorted counts.
     *
     * @param array &$data
     * @param array $sortedCounts
     * @param string $keyToRank
     * @return void
     */
    private function assignRank(&$data, $sortedCounts, $keyToRank)
    {
        $rank = 1;
        $prevCount = null;

        foreach ($sortedCounts as $key => $count) {
            if ($count === 0) {
                $data[$key][$keyToRank] = null; // Skip ranks for count = 0
            } elseif ($count !== $prevCount) {
                $data[$key][$keyToRank] = $rank++;
            } else {
                $data[$key][$keyToRank] = $rank - 1; // Same rank for ties
            }
            $prevCount = $count;
        }
    }

    public function handle()
    {
        try {
            // 1. **Date Handling**
            $startDate = Carbon::now()->startOfMonth()->toDateString(); // First day of current month
            $endDate = Carbon::now()->toDateString();                  // Today
            $lastDay = Carbon::parse($endDate)->subDay()->toDateString();

            // Remove Sundays from last day
            while (Carbon::parse($lastDay)->isSunday()) {
                $lastDay = Carbon::parse($lastDay)->subDay()->toDateString();
            }

            $data = [];
            $lastDayData = [];

            // 2. **Fetch Auditor IDs**
            $auditor_ids = Audit::where('client_id', 9)
                ->whereBetween('audit_date', [$startDate, $endDate])
                ->distinct()
                ->pluck('audited_by_id')
                ->toArray();

              

            if (empty($auditor_ids)) {
                Log::info('No auditors found for the given client and date range.');
                return;
            }

            // 3. **Fetch Users in Bulk**
            $users = User::whereIn('id', $auditor_ids)->get()->keyBy('id');

            // 4. **Process Mapping**
            // Extract all unique process IDs from auditors
            $allProcessIds = [];
            foreach ($users as $user) {
                $processIds = json_decode($user->assigned_process_ids, true) ?? [];
                if (is_array($processIds)) {
                    $allProcessIds = array_merge($allProcessIds, $processIds);
                }
            }
            $allProcessIds = array_unique($allProcessIds);

            // Fetch process names
            $processMap = Process::whereIn('id', $allProcessIds)
                ->pluck('name', 'id')
                ->toArray();

            // 5. **Build Last Day Data**
            foreach ($auditor_ids as $key => $auditorId) {
                $userInfo = $users[$auditorId] ?? null;

                if (!$userInfo) {
                    Log::warning("User with ID {$auditorId} not found.");
                    continue;
                }

                // Fetch qa_target from User table; default to 40 if not set or non-numeric
                $target = is_numeric($userInfo->qa_target) ? (float)$userInfo->qa_target : 40;

                $lastDayAudit = (int) Audit::where('audited_by_id', $auditorId)
                    ->whereDate('audit_date', $lastDay)
                    ->count();

                $lastDayAuditTime = (int) Audit::where('audited_by_id', $auditorId)
                    ->whereDate('audit_date', $lastDay)
                    ->sum('auditor_time_spend_in_secs');

                $lastDayspendHour = $this->secondsToHoursMinutes($lastDayAuditTime);

                // Convert process IDs to names
                $processIds = json_decode($userInfo->assigned_process_ids, true) ?? [];
                $processNames = array_map(function ($id) use ($processMap) {
                    return $processMap[$id] ?? 'Unknown Process';
                }, $processIds);
                $processNames = implode(', ', $processNames);

                $lastDayTimePercentage = $lastDayAuditTime > 0
                    ? $this->calculatePercentage($lastDayAuditTime, 8 * 3600)
                    : '0%';

                $lastDayData[$key]['agentName'] = $userInfo->name;
                $lastDayData[$key]['qdid'] = $userInfo->qd_id ?? 'NA';
                $lastDayData[$key]['LastDayTarget'] = $lastDayAudit > 0 ? $target : 0;
                $lastDayData[$key]['lastDayCount'] = $lastDayAudit;
                $lastDayData[$key]['lastDayAchivedPer'] = $lastDayAudit > 0 ? round(($lastDayAudit / $target) * 100, 2) : 0;
                $lastDayData[$key]['process'] = $processNames;
                $lastDayData[$key]['spendTime'] =  $lastDayspendHour;
                $lastDayData[$key]['LastDayTimeSpentPercentage'] = $lastDayTimePercentage;
            }

            // 6. **Build MTD Data**
            foreach ($auditor_ids as $key => $auditorId) {
                $userInfo = $users[$auditorId] ?? null;

                if (!$userInfo) {
                    Log::warning("User with ID {$auditorId} not found.");
                    continue;
                }

                // Fetch qa_target from User table; default to 40 if not set or non-numeric
                $target = is_numeric($userInfo->qa_target) ? (float)$userInfo->qa_target : 40;

                $MTDauditCount = 0;
                $MTDtarget = 0;
                $present = 0;
                $MonthDayAuditTime = 0;

                // Iterate through each day of the month
                $currentStartDate = Carbon::parse($startDate);
                $currentEndDate = Carbon::parse($endDate);

                while ($currentStartDate->lte($currentEndDate)) {
                    $currentDate = $currentStartDate->toDateString();

                    $currentCycleAudit = (int) Audit::where('audited_by_id', $auditorId)
                        ->whereDate('audit_date', $currentDate)
                        ->count();

                    $MonthDayAuditSec = (int) Audit::where('audited_by_id', $auditorId)
                        ->whereDate('audit_date', $currentDate)
                        ->sum('auditor_time_spend_in_secs');

                    if ($currentCycleAudit > 0) {
                        $MTDauditCount += $currentCycleAudit;
                        $MTDtarget += $target;
                        $present += 1;
                        $MonthDayAuditTime += $MonthDayAuditSec;
                    }

                    $currentStartDate->addDay();
                }

                $MonthDayspendHour  = $this->secondsToHoursMinutes($MonthDayAuditTime);

                $mtdTimeSpentPercentage = $present > 0
                    ? $this->calculatePercentage($MonthDayAuditTime, $present * 8 * 3600)
                    : '0%';

                // Convert process IDs to names
                $processIds = json_decode($userInfo->assigned_process_ids, true) ?? [];
                $processNames = array_map(function ($id) use ($processMap) {
                    return $processMap[$id] ?? 'Unknown Process';
                }, $processIds);
                $processNames = implode(', ', $processNames);

                $data[$key]['agentName'] = $userInfo->name;
                $data[$key]['qdid'] = $userInfo->qd_id ?? 'NA';
                $data[$key]['MTDTarget'] = $MTDtarget;
                $data[$key]['MTDauditCount'] = $MTDauditCount;
                $data[$key]['process'] = $processNames;
                $data[$key]['MtdAchivedPer'] = $MTDtarget > 0 ? round(($MTDauditCount / $MTDtarget) * 100, 2) : 0;
                $data[$key]['presentDays'] = $present;
                $data[$key]['MonthlyTarget'] = $target * 24;
                $data[$key]['MonthlyTargetAchivedPer'] = ($target * 24) > 0 ? round(($MTDauditCount / ($target * 24)) * 100, 2) : 0;
                $data[$key]['MonthDayspendHour'] = $MonthDayspendHour;
                $data[$key]['MTDTimeSpentPercentage'] = $mtdTimeSpentPercentage;
            }

            // 7. **Assign Ranks**
            // Prepare counts for ranking
            $lastDayCounts = array_column($lastDayData, 'lastDayAchivedPer');
            $MTDauditCounts = array_column($data, 'MonthlyTargetAchivedPer');

            // Sort counts descending for ranking
            arsort($lastDayCounts);
            arsort($MTDauditCounts);

            // Assign ranks
            $this->assignRank($lastDayData, $lastDayCounts, 'LastDayRank');
            usort($lastDayData, function ($a, $b) {
                return ($a['LastDayRank'] ?? PHP_INT_MAX) <=> ($b['LastDayRank'] ?? PHP_INT_MAX);
            });

            $this->assignRank($data, $MTDauditCounts, 'MTDRank');
            // Sort data by MTD Rank
            usort($data, function ($a, $b) {
                return $a['MTDRank'] <=> $b['MTDRank'];
            });

            // 10. **Create Excel Spreadsheet**
            $spreadsheet = new Spreadsheet();
            $spreadsheet->removeSheetByIndex(0); // Remove default sheet

            // Create a new sheet
            $sheet = new Worksheet($spreadsheet, 'Productivity Report');
            $spreadsheet->addSheet($sheet);

            // Set default column width for better readability
            $sheet->getDefaultColumnDimension()->setWidth(20);

            // Merge cells for the title
            $sheet->mergeCells('C3:N6');
            $sheet->mergeCells('P3:W6');

            // Set the title
            $sheet->setCellValue('C3', 'Monthly Productivity');
            $sheet->setCellValue('P3', 'Last Day Productivity');

            // Define styles for the title
            $titleStyle = [
                'font' => [
                    'bold' => true,
                    'size' => 18,
                    'color' => ['rgb' => '000000'],
                ],
                'fill' => [
                    'fillType' => Style\Fill::FILL_SOLID,
                    'color' => ['rgb' => 'fbe4d3'],
                ],
                'alignment' => [
                    'horizontal' => Style\Alignment::HORIZONTAL_CENTER,
                    'vertical' => Style\Alignment::VERTICAL_CENTER,
                ],
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Style\Border::BORDER_THIN,
                        'color' => ['rgb' => '000000'],
                    ],
                ],
            ];
            $sheet->getStyle('C3:N6')->applyFromArray($titleStyle);
            $sheet->getStyle('P3:W6')->applyFromArray($titleStyle);

            // Define styles for the headers
            $headerStyle = [
                'font' => [
                    'bold' => true,
                    'size' => 8,
                    'color' => ['rgb' => 'FFFFFF'],
                ],
                'fill' => [
                    'fillType' => Style\Fill::FILL_SOLID,
                    'color' => ['rgb' => '000000'],
                ],
                'alignment' => [
                    'horizontal' => Style\Alignment::HORIZONTAL_CENTER,
                    'vertical' => Style\Alignment::VERTICAL_CENTER,
                ],
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Style\Border::BORDER_THIN,
                        'color' => ['rgb' => '000000'],
                    ],
                ],
            ];

            // Define styles for the data cells
            $dataStyle = [
                'alignment' => [
                    'horizontal' => Style\Alignment::HORIZONTAL_CENTER,
                    'vertical' => Style\Alignment::VERTICAL_CENTER,
                ],
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Style\Border::BORDER_THIN,
                        'color' => ['rgb' => '000000'],
                    ],
                ],
            ];

            // Set headers
            // MTD Columns
            $sheet->setCellValue('C8', 'Agent Name');
            $sheet->setCellValue('D8', 'QD Id');
            $sheet->setCellValue('E8', 'Process');
            $sheet->setCellValue('F8', 'Present Days');
            $sheet->setCellValue('G8', 'MTD Target');
            $sheet->setCellValue('H8', 'MTD Audit Count');
            $sheet->setCellValue('I8', 'MTD Achieved %');
            $sheet->setCellValue('J8', 'Monthly Target');
            $sheet->setCellValue('K8', 'Monthly Achieved %');
            $sheet->setCellValue('L8', 'Monthly Rank');
            $sheet->setCellValue('M8', 'MTD Spend Time');
            $sheet->setCellValue('N8', 'MTD Time Spent on Tool %');

            // Last Day Columns
            $sheet->setCellValue('P8', 'Agent Name');
            $sheet->setCellValue('Q8', 'QD Id');
            $sheet->setCellValue('R8', 'Last Day Target');
            $sheet->setCellValue('S8', 'Last Day Count');
            $sheet->setCellValue('T8', 'Last Day Achieved %');
            $sheet->setCellValue('U8', 'Last Day Rank');
            $sheet->setCellValue('V8', 'Last Day Spend Time');
            $sheet->setCellValue('W8', 'Last Day Time Spent On Tool%');

            // Apply header styles
            $columns = ['C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N',  'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W'];
            foreach ($columns as $column) {
                $cell = $column . '8';
                $sheet->getStyle($cell)->applyFromArray($headerStyle);
            }

            // 11. **Populate MTD Data**
            $row = 9;
            foreach ($data as $value) {
                $sheet->setCellValue('C' . $row, $value['agentName']);
                $sheet->setCellValue('D' . $row, $value['qdid']);
                $sheet->setCellValue('E' . $row, $value['process']);
                $sheet->setCellValue('F' . $row, $value['presentDays']);
                $sheet->setCellValue('G' . $row, $value['MTDTarget']);
                $sheet->setCellValue('H' . $row, $value['MTDauditCount']);
                $sheet->setCellValue('I' . $row, $value['MtdAchivedPer'] . '%');
                $sheet->setCellValue('J' . $row, $value['MonthlyTarget']);
                $sheet->setCellValue('K' . $row, $value['MonthlyTargetAchivedPer'] . '%');
                $sheet->setCellValue('L' . $row, $value['MTDRank']);
                $sheet->setCellValue('M' . $row, isset($value['MonthDayspendHour'])
                    ? "{$value['MonthDayspendHour']['hours']} hours {$value['MonthDayspendHour']['minutes']} Minutes"
                    : 'NA');
                $sheet->setCellValue('N' . $row, $value['MTDTimeSpentPercentage']);

                // Apply data styles
                $sheet->getStyle("C{$row}:N{$row}")->applyFromArray($dataStyle);

                $row++;
            }

            // 12. **Populate Last Day Data**
            $row = 9;
            foreach ($lastDayData as $value) {
                $sheet->setCellValue('P' . $row, $value['agentName']);
                $sheet->setCellValue('Q' . $row, $value['qdid']);
                $sheet->setCellValue('R' . $row, $value['LastDayTarget']);
                $sheet->setCellValue('S' . $row, $value['lastDayCount']);
                $sheet->setCellValue('T' . $row, $value['lastDayAchivedPer'] . '%');
                $sheet->setCellValue('U' . $row, $value['LastDayRank'] ?? 'NA');
                $sheet->setCellValue('V' . $row, isset($value['spendTime'])
                    ? "{$value['spendTime']['hours']} hours {$value['spendTime']['minutes']} Minutes"
                    : 'NA');
                $sheet->setCellValue('W' . $row, $value['LastDayTimeSpentPercentage']);

                // Apply red font color if target not achieved
                if ($value['lastDayAchivedPer'] < 100) {
                    $sheet->getStyle("U{$row}")->getFont()->getColor()->setRGB('FF0000');
                }

                // Apply data styles
                $sheet->getStyle("P{$row}:W{$row}")->applyFromArray($dataStyle);
                $row++;
            }

            // 13. **Auto-size Columns for Readability**
            foreach (['C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W'] as $columnID) {
                $sheet->getColumnDimension($columnID)->setAutoSize(true);
            }

            // 14. **Save the Spreadsheet**
            $filename = storage_path('TATA_Productivity.xlsx');
            $writer = new Xlsx($spreadsheet);
            $writer->save($filename);

            // 15. **Email Settings**
            // $to_emails = ["palak.khandelwal@qdegrees.org"];
           $to_emails = ["sb@qdegrees.com","gurupej.singh@qdegrees.com"];

            // 16. **Send the Email with Attachment**
            Mail::to($to_emails)->send(new tata_productivity($filename));

            // 17. **Log the Success**
            Log::info('TATA Productivity report sent successfully.');
        } catch (\Exception $e) {
            Log::error('Error in sending TATA Finance Productivity report: ' . $e->getMessage());
        }
    }
}
