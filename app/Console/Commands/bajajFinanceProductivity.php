<?php

namespace App\Console\Commands;

use App\Audit;
use App\Mail\bajajauditreport;
use App\Process;
use App\QaTarget;
use App\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\Builder;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style;

class bajajFinanceProductivity extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'auditor:report';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send a Daily email with productivity report of tool client wise';

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
         try {

            function secondsToHoursMinutes($seconds) {
                $hours = floor($seconds / 3600); 
                $minutes = floor(($seconds % 3600) / 60);
                return [
                    'hours'   => $hours,
                    'minutes' => $minutes,
                ];
            }


            function calculatePercentage($seconds, $totalPossibleSeconds) {
                if ($seconds === null || $totalPossibleSeconds === 0) {
                    return '0%';
                }
                $percentage = ($seconds / $totalPossibleSeconds) * 100;
                return round($percentage, 2) . '%';
            }

             // 1. **Date Handling**
             $startDate = date('Y-m-01'); // First day of current month
             $endDate = date('Y-m-d');    // Today
            //  $lastDay = date('Y-m-d', strtotime($endDate . ' -1 day'));
             $lastDay =  date('Y-m-d');
 
             // Remove Sundays from last day
            //  while (date('w', strtotime($lastDay)) == 0) { // 0 represents Sunday in PHP
            //      $lastDay = date('Y-m-d', strtotime($lastDay . ' -1 day'));
            //  }
 
             $data = [];
             $lastDayData = [];
 
             // 2. **Fetch Auditor IDs**
             $auditor_ids = Audit::where('client_id', 23)
                 ->whereDate('audit_date', '>=', $startDate)
                 ->whereDate('audit_date', '<=', $endDate)
                 ->distinct()
                 ->pluck('audited_by_id')
                 ->toArray();
 
             if (empty($auditor_ids)) {
                 Log::info('No auditors found for the given client and date range.');
                 return;
             }
 
             // 3. **Fetch QaTargets and Users in Bulk**
             $qaTargets = QaTarget::whereIn('qa_id', $auditor_ids)
                 ->pluck('qa_target', 'qa_id')
                 ->toArray();
 
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
                 $target = $qaTargets[$auditorId] ?? 40;
                 $userInfo = $users[$auditorId] ?? null;
 
                 if (!$userInfo) {
                     Log::warning("User with ID {$auditorId} not found.");
                     continue;
                 }
 
                 $lastDayAudit = Audit::where('audited_by_id', $auditorId)
                     ->whereDate('audit_date', $lastDay)
                     ->count();

                $lastDayAuditTime = Audit::where('audited_by_id',$auditorId)->whereDate('audit_date', $lastDay)->sum('auditor_time_spend_in_secs'); 
                $lastDayspendHour = secondsToHoursMinutes($lastDayAuditTime);
 
                 // Convert process IDs to names
                 $processIds = json_decode($userInfo->assigned_process_ids, true) ?? [];
                 $processNames = array_map(function($id) use ($processMap) {
                     return $processMap[$id] ?? 'Unknown Process';
                 }, $processIds);
                 $processNames = implode(', ', $processNames);

                 $lastDayTimePercentage = $lastDayAudit > 0 
                    ? calculatePercentage($lastDayAuditTime, 8 * 3600) 
                    : '0%';
 
                 $lastDayData[$key]['agentName'] = $userInfo->name;
                 $lastDayData[$key]['qdid'] = $userInfo->qd_id ?? 'NA';
                 $lastDayData[$key]['LastDayTarget'] = $lastDayAudit > 0 ? $target : 0;
                 $lastDayData[$key]['lastDayCount'] = $lastDayAudit;
                 $lastDayData[$key]['lastDayAchivedPer'] = $lastDayAudit > 0 ? round(($lastDayAudit / 40) * 100) : 0;
                 $lastDayData[$key]['process'] = $processNames;
                 $lastDayData[$key]['spendTime'] =  $lastDayspendHour;
                 $lastDayData[$key]['LastDayTimeSpentPercentage'] = $lastDayTimePercentage;

             }
 
             // 6. **Build MTD Data**
             foreach ($auditor_ids as $key => $auditorId) {
                 $target = $qaTargets[$auditorId] ?? 40;
 
                 $MTDauditCount = 0;
                 $MTDtarget = 0;
                 $present = 0;
                 $MonthDayAuditTime = 0;
                 $MonthDayspendHour = 0;
 
                 $currentStartDate = date('Y-m-01'); // Corrected to 'Y'
 
                 while (strtotime($currentStartDate) <= strtotime($endDate)) {
                     $currentCycleAudit = Audit::where('audited_by_id', $auditorId)
                         ->whereDate('audit_date', $currentStartDate)
                         ->count();

                      $MonthDayAuditSec = Audit::where('audited_by_id',$auditorId)->whereDate('audit_date', $currentStartDate)->sum('auditor_time_spend_in_secs'); 
 
                     if ($currentCycleAudit > 0) {
                         $MTDauditCount += $currentCycleAudit;
                         $MTDtarget += $target;
                         $present += 1;
                         $MonthDayAuditTime +=   $MonthDayAuditSec;
                     }
 
                     $currentStartDate = date("Y-m-d", strtotime("+1 day", strtotime($currentStartDate)));
                 }

                 $MonthDayspendHour  = secondsToHoursMinutes($MonthDayAuditTime);

                 $mtdTimeSpentPercentage = $present > 0 
                    ? calculatePercentage($MonthDayAuditTime, $present * 8 * 3600) 
                    : '0%';
 
                $userInfo = $users[$auditorId] ?? null;
 
                 if (!$userInfo) {
                     Log::warning("User with ID {$auditorId} not found.");
                     continue;
                 }
 
                 // Convert process IDs to names
                 $processIds = json_decode($userInfo->assigned_process_ids, true) ?? [];
                 $processNames = array_map(function($id) use ($processMap) {
                     return $processMap[$id] ?? 'Unknown Process';
                 }, $processIds);
                 $processNames = implode(', ', $processNames);
 
                 $data[$key]['agentName'] = $userInfo->name;
                 $data[$key]['qdid'] = $userInfo->qd_id ?? 'NA';
                 $data[$key]['MTDTarget'] = $MTDtarget;
                 $data[$key]['MTDauditCount'] = $MTDauditCount;
                 $data[$key]['process'] = $processNames;
                 $data[$key]['MtdAchivedPer'] = $MTDtarget > 0 ? round(($MTDauditCount / $MTDtarget) * 100) : 0;
                 $data[$key]['presentDays'] = $present;
                 $data[$key]['MonthlyTarget'] = $target * 24;
                 $data[$key]['MonthlyTargetAchivedPer'] = round(($MTDauditCount / ($target * 24)) * 100);
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
 
             // Function to assign ranks
             function assignRank(&$data, $sortedCounts, $keyToRank)
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

          
         
             assignRank($lastDayData, $lastDayCounts, 'LastDayRank');
             usort($lastDayData, function ($a, $b) {
                return ($a['LastDayRank'] ?? PHP_INT_MAX) <=> ($b['LastDayRank'] ?? PHP_INT_MAX);
            });

            $MTDauditCounts = array_column($data, 'MonthlyTargetAchivedPer');
            arsort($MTDauditCounts);
            assignRank($data, $MTDauditCounts, 'MTDRank');        
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
             $sheet->setCellValue('V8', 'Last Day Spend time');
             $sheet->setCellValue('W8', 'Last Day Time Spent On Tool%'); 

 
             // Apply header styles
            $columns = ['C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L','M','N',  'P', 'Q', 'R', 'S', 'T','U','V','W'];
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
             foreach (['C','D','E','F','G','H','I','J','K','L','M','O','P','Q','R','S','T','U' ,'v','W'] as $columnID) {
                 $sheet->getColumnDimension($columnID)->setAutoSize(true);
             }
 
             // 14. **Save the Spreadsheet**
             $filename = storage_path('BflProductivity.xlsx');
             $writer = new Xlsx($spreadsheet);
             $writer->save($filename);
 
             // 15. **Email Settings**
            //  $to_emails = ["palak.khandelwal@qdegrees.org"];
            // $to_emails = ["sb@qdegrees.com","Jasim.Ali@qdegrees.com","palak.khandelwal@qdegrees.org","kiran.ingulkar@qdegrees.org","Susarla.Prashanth@QDegrees.org","Vibhav@QDegrees.org","Sumith.Kalaiselvan@qdegrees.com","sme@qdegrees.com","Ranjit.Chandran@qdegrees.com","biswadeep.hazra@qdegrees.com"];

            $to_emails = ["sb@qdegrees.com","Jasim.Ali@qdegrees.com","Sumith.Kalaiselvan@qdegrees.com"];
            // $cc_emails = ["sme@qdegrees.com"];
    
 
             // 16. **Send the Email with Attachment**
             Mail::to($to_emails)->send(new bajajauditreport($filename));
 
             // 17. **Log the Success**
             Log::info('Bajaj Finance Productivity report sent successfully.');
         } catch (\Exception $e) {
             Log::error('Error in sending Bajaj Finance Productivity report: ' . $e->getMessage());
         }
     }
}
