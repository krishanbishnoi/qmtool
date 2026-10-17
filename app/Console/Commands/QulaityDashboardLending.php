<?php

namespace App\Console\Commands;

use App\Audit;
use App\AuditResult;
use Illuminate\Console\Command;
use App\Mail\LendingqulatiyDash;
use App\Exports\DataExport;
use App\QmSheetSubParameter;
use App\RawData;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Illuminate\Support\Facades\Mail;
use PhpOffice\PhpSpreadsheet\Style;

class QulaityDashboardLending extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'Lending:Overall';

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

        $startDate = date('Y-m-01');
        $today = date('Y-m-d');

        $All_LOB = RawData::select('raw_data.lob as lob')
            ->where('client_id', 23)
            ->where('status', 1)
            ->whereNotIn('partner_id', [113, 114])
            ->whereDate('updated_at', '>=', $startDate)
            ->whereDate('updated_at', '<=', $today)
            ->distinct()
            ->pluck('lob');


        $spreadsheet = new Spreadsheet();
        $spreadsheet->removeSheetByIndex(0);

        foreach ($All_LOB as $lob) {
            $weekData = [];
            $LOCData = [];
            $dayWise = [];
            $parametric = [];
            $parametricCounts = [];
            $SaleNoneSale = [];
            $SaleNoneSale[] = $this->SalevsNoneSale($lob);
            $weekData[] = $this->WeekwiseScoresNew($lob);
            $LOCData[] = $this->LocationWise($lob);
            $dayWise[] = $this->dayWiseScore($lob);
            $parametric[] = $this->ParametricFailures($lob);
            $parametricCounts[] = $this->ParametricFailuresCounts($lob);
            $this->createSheetWithTables($spreadsheet, $lob, $weekData, $LOCData, $dayWise, $parametricCounts,  $parametric, $SaleNoneSale);
        }



        $filename = storage_path('/multi_sheet_excel.xlsx');
        $writer = new Xlsx($spreadsheet);
        $writer->save($filename);


        $subject = "BFL LOB Wise Quality Dashboard";
        $to_emails = ["Rahul.adiwal@bajajfinserv.in","Sagar.Khajekar@bajajfinserv.in"];    
        $cc_emails = ["Manmitraj.Mahajan@bajajfinserv.in","Jasim.Ali@qdegrees.com","shubhda.sharma@qdegrees.com","palak.khandelwal@qdegrees.org","kiran.ingulkar@qdegrees.org","Sumith.Kalaiselvan@qdegrees.com","NISHA.KRISHNA@qdegrees.com","SME.BFL@QDegrees.org","Ranjit.Chandran@qdegrees.com"];

        Mail::to($to_emails)->cc($cc_emails)->send(new LendingqulatiyDash($subject, $filename));

        return 0;
    }

    private function createSheetWithTables($spreadsheet, $lob, $weekData, $LOCData, $dayWise, $parametricCounts,  $parametric, $SaleNoneSale)
    {
        $sheet = new Worksheet($spreadsheet, $lob);
        $spreadsheet->addSheet($sheet);

        $sheet->getDefaultColumnDimension()->setWidth(20);
        $sheet->mergeCells('B2:P6');

        // Set cell value
        $sheet->setCellValue('B2', 'Quality Dashboard');

        // Apply styles
        $style = [
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
        $sheet->getStyle('B2:P4')->applyFromArray($style);

        $styletoheaders = [
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

        ];
        $sheet->getStyle('B2:P4')->applyFromArray($style);

        $totalRowStyle = [

            'font' => [
                'bold' =>  true,
                'color' => ['rgb' => 'FFFFFF'],
            ],
            'alignment' => [
                'horizontal' => Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => Style\Alignment::VERTICAL_CENTER,
            ],
            'fill' => [
                'fillType' => Style\Fill::FILL_SOLID,
                'color' => ['rgb' => '000000'],
            ],
        ];

        $rowsStyle = [
            'font' => [
                'bold' =>  false,
                'color' => ['rgb' => '000000'],
            ],
            'fill' => [
                'fillType' => Style\Fill::FILL_SOLID,
                'color' => ['rgb' => 'fbe4d3'],
            ],
        ];
        $alignment = [

            'alignment' => [
                'horizontal' => Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => Style\Alignment::VERTICAL_CENTER,
            ],

        ];

        $columns = ['B', 'C', 'D', 'F', 'G', 'H', 'J', 'K', 'L', 'N', 'O'];
        $Seccolumns = ['B', 'C', 'D', 'F', 'G', 'H'];
        foreach ($columns as $column) {
            $cell = $column . '10';
            $sheet->getStyle($cell)->applyFromArray($styletoheaders);
        }
        foreach ($Seccolumns as $column) {
            $cell30 =  $column . '20';
            $sheet->getStyle($cell30)->applyFromArray($styletoheaders);
        }


        $sheet->setCellValue('B10', 'Week');
        $sheet->setCellValue('C10', 'AuditCount');
        $sheet->setCellValue('D10', 'Score With Fatal');

        $row = 11;

        $totalCount = 0;
        $QS = 0;

        foreach ($weekData as $key => $value) {
            foreach ($value as $keys => $eachValue) {
                if ($keys === 'overallQS') {
                    $QS = $eachValue;
                    continue;
                }
                $sheet->setCellValue('B' . $row, $eachValue['weekLabels']);
                $sheet->setCellValue('C' . $row, $eachValue['auditCount']);
                $sheet->setCellValue('D' . $row, $eachValue['WeekLOBWise'] . '%');
                $totalCount += $eachValue['auditCount'];
                $sheet->getStyle('B' .   $row . ':D'   . $row)->applyFromArray($rowsStyle);
                $sheet->getStyle('B' .   $row . ':D'   . $row)->applyFromArray($alignment);
                $row++;
            }

            $sheet->setCellValue('B' . $row, 'Grand Total');
            $sheet->setCellValue('C' . $row,  $totalCount);
            $sheet->setCellValue('D' . $row,  $QS  . '%');
            $sheet->getStyle('B' .   $row . ':D'   . $row)->applyFromArray($totalRowStyle);
        }


        $sheet->setCellValue('F10', 'Location');
        $sheet->setCellValue('G10', 'AuditCount');
        $sheet->setCellValue('H10', 'Score With Fatal');

        $rowLoc = 11;
        $totalLoc = 0;
        $QSPer = 0;

        foreach ($LOCData as $key => $value) {
            foreach ($value as $keys => $each) {
                if ($keys === 'OverallLocQS') {
                    $QSPer = $each;
                    continue;
                }
                $sheet->setCellValue('F' . $rowLoc, $each['location']);
                $sheet->setCellValue('G' . $rowLoc, $each['LOCauditCount']);
                $sheet->setCellValue('H' . $rowLoc, $each['LOCLOBWiseQS'] . '%');
                $totalLoc += $each['LOCauditCount'];
                $sheet->getStyle('F' .   $rowLoc . ':H'   . $rowLoc)->applyFromArray($rowsStyle);
                $sheet->getStyle('F' .   $rowLoc . ':H'   . $rowLoc)->applyFromArray($alignment);
                $rowLoc++;
            }
            $sheet->setCellValue('F' . $rowLoc, 'Grand Total');
            $sheet->setCellValue('G' . $rowLoc,  $totalLoc);
            $sheet->setCellValue('H' . $rowLoc,  $QSPer  . '%');
            $sheet->getStyle('F' .   $rowLoc . ':H'   . $rowLoc)->applyFromArray($totalRowStyle);
        }

        $sheet->setCellValue('J10', 'Parameter Wise');
        $sheet->setCellValue('K10', 'Score %');
        $sheet->setCellValue('L10', 'Score with fatal');

        $rowDayparas = 11;

        foreach ($parametric as $parametricData) {
            if (isset($parametricData['parameter'])) {
                foreach ($parametricData['parameter'] as $each) {
                    $subparameter = QmSheetSubParameter::select('sub_parameter')->where('id', $each['sub_parameter_id'])->first();
                    $cleanedSubparameter = preg_replace('/[^a-z0-9\s]/i', '', $subparameter->sub_parameter);
                    $sheet->setCellValue('J' . $rowDayparas, $cleanedSubparameter);
                    $sheet->setCellValue('K' . $rowDayparas, $each['percentageScore'] . '%');
                    $sheet->setCellValue('L' . $rowDayparas, $each['percentageWithFatal'] . '%');
                    $sheet->getStyle('J' . $rowDayparas . ':L' . $rowDayparas)->applyFromArray($rowsStyle);
                    $sheet->getStyle('K' . $rowDayparas . ':L' . $rowDayparas)->applyFromArray($alignment);
                    $rowDayparas++;
                }

                if (isset($parametricData['totals'])) {
                    $sheet->setCellValue('J' . $rowDayparas, 'Grand Total');
                    $sheet->setCellValue('K' . $rowDayparas, $parametricData['totals']['totalQsWithoutFatal'] . '%');
                    $sheet->setCellValue('L' . $rowDayparas, $parametricData['totals']['totalQsfatal'] . '%');
                    $sheet->getStyle('J' . $rowDayparas . ':L' . $rowDayparas)->applyFromArray($totalRowStyle);
                    $rowDayparas++;
                }
            }
        }


        $sheet->setCellValue('N10', 'Parameter Wise ');
        $sheet->setCellValue('O10', 'Critical Defects');

        $rowDaypara = 11;
        $totalLocCounts = 0;
        foreach ($parametricCounts as $key => $value) {
            foreach ($value as $keys => $each) {
                $subparameter = QmSheetSubParameter::select('sub_parameter')->where('id', $each['sub_parameter_id'])->first();
                $cleanedSubparameter = preg_replace('/[^a-z0-9\s]/i', '', $subparameter->sub_parameter);
                $sheet->setCellValue('N' .  $rowDaypara,  $cleanedSubparameter);
                $sheet->setCellValue('O' .  $rowDaypara, $each['audit_count']);

                $totalLocCounts += $each['audit_count'];
                $sheet->getStyle('N' .   $rowDaypara . ':O'   . $rowDaypara)->applyFromArray($rowsStyle);
                $sheet->getStyle('O'   . $rowDaypara)->applyFromArray($alignment);
                $rowDaypara++;
            }
            $sheet->setCellValue('N' .  $rowDaypara, 'Grand Total');
            $sheet->setCellValue('O' .  $rowDaypara,  $totalLocCounts);

            $sheet->getStyle('N' .   $rowDaypara . ':O'   . $rowDaypara)->applyFromArray($totalRowStyle);
        }



        $sheet->setCellValue('B20', 'Day Wise');
        $sheet->setCellValue('C20', 'Audit Count');
        $sheet->setCellValue('D20', 'Score With Fatal');

        $rowDay = 21;
        $totalLocday  = 0;
        foreach ($dayWise as $key => $value) {
            foreach ($value as $keys => $each) {
                if ($keys === 'totalQS') {
                    $QSPer = $each;
                    continue;
                }
                $sheet->setCellValue('B' .  $rowDay, $each['date']);
                $sheet->setCellValue('C' .  $rowDay, $each['audit_count']);
                $sheet->setCellValue('D' .  $rowDay, $each['quality_score'] . '%');
                $totalLocday += $each['audit_count'];
                $sheet->getStyle('B' .   $rowDay . ':D'   . $rowDay)->applyFromArray($rowsStyle);
                $sheet->getStyle('B' .   $rowDay . ':D'   . $rowDay)->applyFromArray($alignment);
                $rowDay++;
            }
            $sheet->setCellValue('B' .  $rowDay, 'Grand Total');
            $sheet->setCellValue('C' .  $rowDay,  $totalLocday);
            $sheet->setCellValue('D' .  $rowDay,  $QSPer  . '%');
            $sheet->getStyle('B' .   $rowDay . ':D'   . $rowDay)->applyFromArray($totalRowStyle);
        }


        $SaleStyle = [
            'font' => [
                'bold' =>  false,
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
        ];

        $Salecolumns = ['G21', 'G22', 'H21', 'H22', 'F22', 'F21', 'F22'];
        foreach ($Salecolumns as $column) {
            $sheet->getStyle($column)->applyFromArray($SaleStyle);
        }

        $sheet->setCellValue('F20', 'SaleVsNonSale');
        $sheet->setCellValue('G20', 'Sale');
        $sheet->setCellValue('H20', 'None Sale');
        $sheet->setCellValue('F21', 'Audit Counts');
        $sheet->setCellValue('F22', 'Score With Fatal');


        foreach ($SaleNoneSale as $key => $value) {
            foreach ($value as $keys => $each) {
                $sheet->setCellValue('G21', $each['Saleaudits']);
                $sheet->setCellValue('G22', $each['nonsaleAudits']);
                $sheet->setCellValue('H21', $each['SaleQualityScore'] . '%');
                $sheet->setCellValue('H22', $each['NonSaleQualityScore'] . '%');
            }
        }
    }

    public function getWeekwiseDates($startDate, $endDate)
    {
        $start = new \DateTime($startDate);
        $end = new \DateTime($endDate);
        $end->modify('next sunday');
        $interval = new \DateInterval('P1W');
        $datePeriod = new \DatePeriod($start, $interval, $end);
        $weeks = [];
        foreach ($datePeriod as $date) {
            $weekStart = $date->format('Y-m-d');
            $weekEnd = (clone $date)->modify('+6 days')->format('Y-m-d');
            $weeks[] = ['week_start' => $weekStart, 'week_end' => $weekEnd];
        }
        return $weeks;
    }

    public function WeekwiseScoresNew($lob)
    {
        $startDate = date('Y-m-01');
        $endDate = date('Y-m-t');
        $dates = $this->getWeekwiseDates($startDate, $endDate);
        $count = 0;
        $overallQualityScore =  0;
        $OverallwithoutFatal =  0;
        $overallWithFatal =  0;
        for ($i = 0; $i <= 4; $i++) {
            if ($dates[$i]['week_end'] < date('Y-m-t')) {
                $count++;
                $data[$i]['weekLabels'] = 'Week ' . $count;
                $weekWiserawdataID = RawData::where('client_id', 23)->whereNotIn('partner_id', [113, 114])->where('lob', $lob)->where('status', 1)
                    // ->whereBetween('updated_at', [$dates[$i]['week_start'], $dates[$i]['week_end']])
                    ->whereDate('updated_at', '>=', $dates[$i]['week_start'])
                    ->whereDate('updated_at', '<=', $dates[$i]['week_end'])
                    ->pluck('id');
                $weekLOBWiseData = Audit::whereNotIn('partner_id', [113, 114])->whereIn('raw_data_id', $weekWiserawdataID)->get();
                $totalAudits = $weekLOBWiseData->count();
                $data[$i]['auditCount'] = $totalAudits;
                $overAllSum = $weekLOBWiseData->sum('overall_score');
                $OverallwithoutFatal += $overAllSum;
                $withFatalSum = $weekLOBWiseData->sum('with_fatal_score_per');
                $overallWithFatal += $withFatalSum;
                $qualityScore = $overAllSum ? round(($withFatalSum / $overAllSum) * 100) : 0;
                $data[$i]['WeekLOBWise'] = $qualityScore;
            }
        }
        $overallQualityScore =  $OverallwithoutFatal ?  round(($overallWithFatal / $OverallwithoutFatal) * 100) :   0;
        $data['overallQS'] =   $overallQualityScore;
        return $data;
    }

    public function LocationWise($lob)
    {

        $startDate = date('Y-m-01');
        $endDate = date('Y-m-d');

        // $today = date('Y-m-d');

        // $day =  date('Y-m-d', strtotime('-1 day', strtotime($today)));

        $locations = RawData::where('client_id', 23)
            ->where('lob', $lob)
            ->where('status', 1)
            ->whereNotIn('partner_id', [113, 114])
            ->whereDate('updated_at', '>=', $startDate)
            ->whereDate('updated_at', '<=', $endDate)
            ->distinct()
            ->pluck('location');

        $locData = [];
        $overallQS =  0;
        $WithFatl =   0;
        $withoutFatal = 0;
        foreach ($locations as $loc) {
            $LOCWiserawdataID = RawData::where('client_id', 23)->whereNotIn('partner_id', [113, 114])->where('lob', $lob)->where('location', $loc)->where('status', 1)->whereDate('updated_at', '>=', $startDate)
                ->whereDate('updated_at', '<=', $endDate)->pluck('id');
            $LOCLOBWiseData = Audit::whereNotIn('partner_id', [113, 114])->whereIn('raw_data_id', $LOCWiserawdataID)->get();
            $totalAudits = $LOCWiserawdataID->count();
            $overAllSum = $LOCLOBWiseData->sum('overall_score');
            $withoutFatal += $overAllSum;
            $withFatalSum = $LOCLOBWiseData->sum('with_fatal_score_per');
            $WithFatl += $withFatalSum;
            $qualityScore = $overAllSum ? round(($withFatalSum / $overAllSum) * 100) : 0;
            $locData[$loc] = [
                'location' => $loc,
                'LOCauditCount' => $totalAudits,
                'LOCLOBWiseQS' => $qualityScore
            ];
        }
        $overallQS = $withoutFatal ? round(($WithFatl /  $withoutFatal) *    100) :     0;
        $locData['OverallLocQS'] =  $overallQS;
        return $locData;
    }

    //day wise

    public function dayWiseScore($lob)
    {

        $startDate = Carbon::now()->startOfMonth();
        $today = Carbon::now()->endOfDay();

        $totalQS = 0;
        $withoutFatal = 0;
        $withFatal = 0;
        $dayWiseData = [];
        for ($date = $startDate; $date->lte($today); $date->addDay()) {
            $dayRawDataIDs = RawData::where('client_id', 23)
                ->whereNotIn('partner_id', [113, 114])
                ->where('lob', $lob)
                ->where('status', 1)
                ->whereDate('updated_at', $date)
                ->pluck('id');

            $dayLOBWiseData = Audit::whereNotIn('partner_id', [113, 114])
                ->whereIn('raw_data_id', $dayRawDataIDs)
                ->get();

            $dayTotalAudits = $dayLOBWiseData->count();
            if ($dayTotalAudits > 0) {

                $dayOverallSum = $dayLOBWiseData->sum('overall_score');
                $dayWithFatalSum = $dayLOBWiseData->sum('with_fatal_score_per');

                $withoutFatal += $dayOverallSum;
                $withFatal += $dayWithFatalSum;

                $qualityScore = $dayOverallSum ? round(($dayWithFatalSum / $dayOverallSum) * 100) : 0;

                $dayWiseData[] = [
                    'date' => $date->toDateString(),
                    'audit_count' => $dayTotalAudits,
                    'quality_score' => $qualityScore,
                ];
            }
        }

        $totalQS = $withoutFatal ? round(($withFatal / $withoutFatal) * 100) : 0;
        $dayWiseData['totalQS'] = $totalQS;

        return $dayWiseData;
        // return [
        //     'dayWiseData' => $dayWiseData,
        //     'totalQS' => $totalQS,
        // ];
    }


    public function ParametricFailures($lob)
    {

        // $day = Carbon::now()->subDays(3);
        $startDate = date('Y-m-01');
        $endDate = date('Y-m-d');

        $parameter = [];
        $totalQs =  $totalScore = $totalfatal = $countfatal = $totalQsWithoutFatal = $totalQsfatal = 0;
        $auditIds = Audit::with('raw_data')->whereHas('raw_data', function ($query) use ($lob) {
            $query->where('lob', $lob)->where('client_id', 23);
        })->whereDate('audit_date', '>=', $startDate)
            ->whereDate('audit_date', '<=', $endDate)->pluck('id');
        $auditResults = AuditResult::whereIn('audit_id', $auditIds)
            ->join('qm_sheet_sub_parameters', 'audit_results.sub_parameter_id', '=', 'qm_sheet_sub_parameters.id')
            ->where('audit_results.is_non_scoring', '!=', 1)
            // ->where('audit_results.selected_option','!=', 2)
            ->where('qm_sheet_sub_parameters.weight', '!=', 0)
            ->select(
                'audit_results.sub_parameter_id',
                \DB::raw('COUNT(*) as audit_count'),
                \DB::raw('SUM(audit_results.score) as WithFatal'),
                \DB::raw('SUM(audit_results.after_audit_weight) as WithoutFatal'),
                'qm_sheet_sub_parameters.weight as sub_parameter_weight'
            )
            ->groupBy('audit_results.sub_parameter_id')
            ->orderBy('audit_count', 'desc')
            ->get();

        foreach ($auditResults as $result) {
            $actualWeight = $result->sub_parameter_weight;
            $percentageScore = round(($result->WithoutFatal / ($result->audit_count * $actualWeight)) * 100);
            $percentageWithFatal = round(($result->WithFatal / ($result->audit_count * $actualWeight)) * 100);
            $totalScore += $result->WithoutFatal;
            $totalfatal +=  $result->WithFatal;
            $countfatal += $result->audit_count * $actualWeight;

            $parameter[] = [
                'sub_parameter_id' => $result->sub_parameter_id,
                'percentageScore' => $percentageScore,
                'percentageWithFatal' => $percentageWithFatal,
            ];
        }
        $totalQsfatal =  round(($totalfatal / $countfatal) * 100);
        $totalQsWithoutFatal =  round(($totalScore /  $countfatal) * 100);

        return [
            'parameter' => $parameter,
            'totals' => [
                'totalQsfatal' => $totalQsfatal,
                'totalQsWithoutFatal' => $totalQsWithoutFatal
            ]
        ];
    }

    public function ParametricFailuresCounts($lob)
    {

        // $day = Carbon::now()->subDays(3);
        $startDate = date('Y-m-01');
        $endDate = date('Y-m-d');
        $parameterWiseCounts = [];
        $auditIds = Audit::with('raw_data')->whereHas('raw_data', function ($query) use ($lob) {
            $query->where('lob', $lob)->where('client_id', 23);
        })->whereDate('audit_date', '>=', $startDate)
            ->whereDate('audit_date', '<=', $endDate)->pluck('id');
        $auditResults = AuditResult::whereIn('audit_id', $auditIds)
            ->join('qm_sheet_sub_parameters', 'audit_results.sub_parameter_id', '=', 'qm_sheet_sub_parameters.id')
            ->where('audit_results.is_non_scoring', '!=', 1)
            ->where('audit_results.selected_option', 2)
            ->where('qm_sheet_sub_parameters.weight', '!=', 0)
            ->select('audit_results.sub_parameter_id', \DB::raw('COUNT(*) as audit_count'))
            ->groupBy('audit_results.sub_parameter_id')
            ->orderBy('audit_count', 'desc')
            ->get();
        foreach ($auditResults as $result) {
            $actualWeight = $result->sub_parameter_weight;
            $parameterWiseCounts[] = [
                'sub_parameter_id' => $result->sub_parameter_id,
                'audit_count' => $result->audit_count,
            ];
        }

        return  $parameterWiseCounts;
    }

    public function SalevsNoneSale($lob)
    {

        $startDate = date('Y-m-01');
        $today = date('Y-m-d');

        $lobDataSale = [];
        $totalQsSale = $totalNonSale = 0;
        $salefatalQs = $saleoverallQS = 0;
        $NoNsalefatalQs = $NoNsaleoverallQS = 0;

        $saleAuditData = Audit::with('raw_data')
            ->whereHas('raw_data', function (Builder $query) use ($lob) {
                $query->where('client_id', 23)
                    ->where('status', 1)
                    ->whereNotIn('partner_id', [113, 114])
                    ->where('lob', $lob)
                    ->where('disposition', 'like', 'sale');
            })
            ->whereDate('audit_date', '>=', $startDate)
            ->whereDate('audit_date', '<=', $today)
            ->get();

        $saleAuditCount = $saleAuditData->count();
        $saleWithfatal = $saleAuditData->sum('with_fatal_score_per');
        $saleWithoutfatal = $saleAuditData->sum('overall_score');
        $slaeQs = ($saleWithoutfatal > 0) ? round(($saleWithfatal / $saleWithoutfatal) * 100) : 0;


        $nonSaleAuditData = Audit::with('raw_data')
            ->whereHas('raw_data', function (Builder $query) use ($lob) {
                $query->where('client_id', 23)
                    ->where('lob', $lob)
                    ->whereNotIn('partner_id', [113, 114])
                    ->where('status', 1)
                    ->where('disposition', 'not like', 'sale');
            })
            ->whereDate('audit_date', '>=', $startDate)
            ->whereDate('audit_date', '<=', $today)
            ->get();

        $nonSaleAuditCount = $nonSaleAuditData->count();
        $nonSaleWithfatal = $nonSaleAuditData->sum('with_fatal_score_per');
        $nonSaleWithoutfatal = $nonSaleAuditData->sum('overall_score');
        $NonSaleQs = ($nonSaleWithoutfatal > 0) ? round(($nonSaleWithfatal / $nonSaleWithoutfatal) * 100) : 0;

        $lobDataSale[] = [
            'Saleaudits' => $saleAuditCount,
            'SaleQualityScore' => $slaeQs,
            'nonsaleAudits' =>  $nonSaleAuditCount,
            'NonSaleQualityScore' => $NonSaleQs
        ];

        return  $lobDataSale;
    }
}
