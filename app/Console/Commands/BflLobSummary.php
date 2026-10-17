<?php

namespace App\Console\Commands;

use App\Mail\Bfl_lobWIse_summry;
use Carbon\Carbon;
use App\Audit;
use App\AuditResult;
use App\RawData;
use App\QmSheetSubParameter;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Illuminate\Database\Eloquent\Builder;
use PDF;
use GuzzleHttp\Client;



class BflLobSummary extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'Bfl_combined:process';

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

        $startDate = Carbon::now()->startOfWeek();
        $endDate = Carbon::now()->endOfWeek();

        $All_LOB = RawData::select('raw_data.lob as lob')
            ->where('client_id', 23)
            ->whereNotIn('partner_id' , [113,114] )
            ->whereNot('process_id' ,88)
            ->where('status', 1)
            ->whereBetween('updated_at', [$startDate, $endDate])
            ->distinct()
            ->pluck('lob');
          

        foreach ($All_LOB as $lob) {

            // overall Lob WIse
            $weekData = [];
            for ($i = 0; $i < 5; $i++) {

                $weekStartDate = Carbon::now()->startOfWeek()->subWeeks($i)->toDateString();
                $weekEndDate = Carbon::now()->endOfWeek()->subWeeks($i)->toDateString();

                $weekAuditData = Audit::whereHas('raw_data', function ($query) use ($lob) {
                    $query->where('client_id', 23)
                        ->where('lob', $lob)
                        ->where('status', 1);
                })->whereBetween('audit_date', [$weekStartDate, $weekEndDate])->get();

                $auditCountsWeekwise = $weekAuditData->count();
                $withFatalScore = $weekAuditData->sum('with_fatal_score_per');
                $withoutFatalScore = $weekAuditData->sum('overall_score');

                $weekData[] = [
                    'auditCountsWeekwise' => $auditCountsWeekwise,
                    'OveallqualityScore' => ($withoutFatalScore > 0) ? round(($withFatalScore / $withoutFatalScore) * 100) : 0
                ];
            }


            $audits = $qualityScore = $week = [];
            foreach ($weekData as $key => $value) {
                $audits[] =  $value['auditCountsWeekwise'];
                $qualityScore[] = $value['OveallqualityScore'];
                $week[] = 'Week ' . ($key + 1);
            }
            $audits = array_reverse($audits);
            $qualityScore = array_reverse($qualityScore);
            $overallTrend =
                "{'type':'bar','data':{'labels':" .
                json_encode($week) .
                ",'datasets':[ {'type':'line','label':'Quality score','borderColor':'#ED7A2C','borderWidth':2,'fill':false,'data': [" .
                implode(',', $qualityScore) .
                "],datalabels: {display: true,color : '#040404',align: 'top',font: {size: 8,weight: 'bold'},formatter: function(value, context) {return value + '%';},},},{'type':'bar','label':'Audit Counts','backgroundColor':'#5B9BD5','data':" .
                json_encode($audits) .
                ",datalabels:{display: true,color : '#040404','backgroundColor':'#8FAADC', anchor: 'start',align: 'end',offset: 0,font: {size: 8,weight: 'bold',}},}]},'options':{'responsive':true,'title':{'display':true,'text':' ',backgroundColor: '#5B9BD5',fontSize: 8},legend: {labels: {fontSize:8,},'position':'bottom',},'tooltips':{'mode':'index','intersect':false},'scales':{'xAxes':[{gridLines: {'display': true, zeroLineWidth: 1, 'zeroLineColor' : 'black' , 'drawTicks' : false , 'drawOnChartArea' : false,},ticks: {fontSize: 8},}],yAxes:[{type: 'linear',display: false,position: 'left',gridLines: {display : false,zeroLineColor: 'black',lineWidth: 0, drawBorder: false,drawTicks : false ,drawOnChartArea: false,},ticks: {display: false , beginAtZero: true}}]}}}";


            $overallTrendImage = $this->generateChartImage($overallTrend);

            $locations = RawData::where('client_id', 23)
                ->where('lob', $lob)
                ->whereNotIn('partner_id' , [113,114] )
                ->where('status', 1)
                ->whereBetween('updated_at', [$startDate, $endDate])
                ->distinct()
                ->pluck('location');


            $locationData = [];
            $weekDataLocation = [];
            $LoccumulativePercentages = [];
            $locData = [];

            foreach ($locations as $loc) {

                $locationData[$loc] = [
                    'lob' => $lob,
                    'LOCQualityScore' => 0,
                    'auditCounts' => 0,
                    'TLData' => [],
                    'SLAData' => []
                ];

                // Location wise Audits data
                $auditData = Audit::whereHas('raw_data', function ($query) use ($lob, $loc) {
                    $query->where('client_id', 23)
                        ->where('lob', $lob)
                        ->where('location', $loc)
                        ->where('status', 1);
                })->whereBetween('audit_date', [$startDate, $endDate])
                    ->get();
                $auditCounts = $auditData->count();
                if ($auditCounts > 0) {
                    $LOCwithfatalscore = $auditData->sum('with_fatal_score_per');
                    $LOCwithoutfatalscore = $auditData->sum('overall_score');
                    $locationData[$loc]['auditCounts'] = $auditCounts;
                    $locationData[$loc]['LOCQualityScore'] =  ($LOCwithoutfatalscore > 0) ?  round(($LOCwithfatalscore / $LOCwithoutfatalscore) * 100) : 0;
                }


                // Location wise trends of overall
                $weekDataLocation[$loc] = [];
                for ($i = 0; $i < 5; $i++) {
                    $weekStartDate = Carbon::now()->startOfWeek()->subWeeks($i)->toDateString();
                    $weekEndDate = Carbon::now()->endOfWeek()->subWeeks($i)->toDateString();

                    $weekLocationAuditData = Audit::whereHas('raw_data', function ($query)  use ($lob, $loc) {
                        $query->where('client_id', 23)
                            ->where('lob', $lob)
                            ->where('location', $loc)
                            ->where('status', 1);
                    })->whereBetween('audit_date', [$weekStartDate, $weekEndDate])
                        ->get();

                    $auditLocationOverall = $weekLocationAuditData->count();
                    $withfatascorelocation =   $weekLocationAuditData->sum('with_fatal_score_per');
                    $withoutFatalScorelocation = $weekLocationAuditData->sum('overall_score');

                    // $weekDataLocation[$loc]['auditLocationOverall'] = $auditLocationOverall;
                    // $weekDataLocation[$loc]['OveallLocationqualityScore'] =  ($withoutFatalScorelocation > 0) ?  round(($withfatascorelocation / $withoutFatalScorelocation) * 100) : 0;
                    $weekDataLocation[$loc][] = [
                        'auditLocationOverall' => $auditLocationOverall,
                        'OveallLocationqualityScore' => ($withoutFatalScorelocation > 0) ?  round(($withfatascorelocation / $withoutFatalScorelocation) * 100) : 0
                    ];
                }



                //Tl wise
                $tls = RawData::where('client_id', 23)
                    ->where('lob', $lob)
                    ->whereNotIn('partner_id' , [113,114] )
                    ->whereNot('process_id' ,88)
                    ->where('location', $loc)
                    ->whereBetween('updated_at', [$startDate, $endDate])
                    ->distinct()
                    ->pluck('tl');
                foreach ($tls as $tl) {

                    $auditDataTL = $auditData->filter(function ($item) use ($loc, $tl) {
                        return $item->raw_data->location === $loc && $item->raw_data->tl === $tl;
                    });
                    $auditCountTLs = $auditDataTL->count();
                    if ($auditCountTLs) {
                        $TLWisewithfatalScore = $auditDataTL->sum('with_fatal_score_per');
                        $TLWisewithoutfatalScore = $auditDataTL->sum('overall_score');
                        $locationData[$loc]['TLData'][$tl] = [
                            'auditCountTLs' => $auditCountTLs,
                            'TLQualtiyScore' => ($TLWisewithoutfatalScore > 0) ?  round(($TLWisewithfatalScore / $TLWisewithoutfatalScore) * 100) : 0
                        ];
                    }
                }


                // SLA MANAGER WISE
                $slaManager = RawData::where('client_id', 23)
                    ->where('lob', $lob)
                    ->whereNotIn('partner_id' , [113,114] )
                    ->whereNot('process_id' ,88)
                    ->where('location', $loc)
                    ->whereBetween('updated_at', [$startDate, $endDate])
                    ->distinct()
                    ->pluck('info_4');
                foreach ($slaManager as $Slm) {
                    $auditDataSLA = $auditData->filter(function ($item) use ($loc, $Slm) {
                        return $item->raw_data->location === $loc && $item->raw_data->info_4 === $Slm;
                    });
                    $auditCountsSLA = $auditDataSLA->count();
                    if ($auditCountsSLA > 0) {
                        $SlaWisewithfatalScore = $auditDataSLA->sum('with_fatal_score_per');
                        $SlaWisewithoutfatalScore = $auditDataSLA->sum('overall_score');
                        $locationData[$loc]['SLAData'][$Slm] = [
                            'auditCountsSLA' => $auditCountsSLA,
                            'SLAQualityScore' => ($SlaWisewithoutfatalScore > 0) ?  round(($SlaWisewithfatalScore / $SlaWisewithoutfatalScore) * 100) : 0
                        ];
                    }
                }

                // Sale vs non Sale
                $LOCsaleAuditData = Audit::with('raw_data')
                    ->whereHas('raw_data', function (Builder $query) use ($lob, $loc) {
                        $query->where('client_id', 23)
                            ->where('status', 1)
                            ->where('lob', $lob)
                            ->where('location', $loc)
                            ->where('disposition', 'like', 'sale');
                    })
                    ->whereBetween('audit_date', [$startDate, $endDate])
                    ->get();
                $LocSaleAuditCount = $LOCsaleAuditData->count();
                $LocSaleWithfatal = $LOCsaleAuditData->sum('with_fatal_score_per');
                $LocSaleWithoutfatal = $LOCsaleAuditData->sum('overall_score');


                $LOCNonSaleAuditData = Audit::with('raw_data')
                    ->whereHas('raw_data', function (Builder $query) use ($lob, $loc) {
                        $query->where('client_id', 23)
                            ->where('status', 1)
                            ->where('lob', $lob)
                            ->where('location', $loc)
                            ->where('disposition', 'not like', 'sale');
                    })
                    ->whereBetween('audit_date', [$startDate, $endDate])
                    ->get();

                $LOCNonSaleAuditCount = $LOCNonSaleAuditData->count();
                $LOCNonSaleWithfatal = $LOCNonSaleAuditData->sum('with_fatal_score_per');
                $LOCNonSaleWithoutfatal = $LOCNonSaleAuditData->sum('overall_score');
                // // Store QC scores


                $locData[$loc]['sale'] = [
                    'LOCSaleaudits' => $LocSaleAuditCount,
                    'LOCSaleQualityScore' => ($LocSaleWithoutfatal > 0) ? round($LocSaleWithfatal /  $LocSaleWithoutfatal) : 0
                ];

                $locData[$loc]['nonSale'] = [
                    'LOCnonsaleAudits' =>  $LOCNonSaleAuditCount,
                    'LOCNonSaleQualityScore' => ($LOCNonSaleWithoutfatal  > 0) ? round($LOCNonSaleWithfatal  /  $LOCNonSaleWithoutfatal) : 0
                ];

                // Location Wise pareto
                $LocauditIds = Audit::with('raw_data')->whereHas('raw_data', function ($query) use ($lob, $loc) {
                    $query->where('lob', $lob)
                        ->where('location', $loc);
                })->whereBetween('audit_date', [$startDate, $endDate])->pluck('id');

                $LocauditResults = AuditResult::whereIn('audit_id', $LocauditIds)
                ->join('qm_sheet_sub_parameters', 'audit_results.sub_parameter_id', '=', 'qm_sheet_sub_parameters.id')
                ->where('audit_results.is_non_scoring', '!=', 1)
                ->where('audit_results.selected_option', 2)
                ->where('qm_sheet_sub_parameters.weight', '!=', 0)
                ->select('audit_results.sub_parameter_id', \DB::raw('COUNT(*) as audit_count'))
                ->groupBy('audit_results.sub_parameter_id')
                ->orderBy('audit_count', 'desc')
                ->get();
                // dd( $LocauditResults);

                // Calculate cumulative percentages
                $totalAudits = $LocauditResults->sum('audit_count');
                $LoccumulativeCount = 0;
                // $LoccumulativePercentages = [];

                foreach ($LocauditResults as $result) {
                    $LoccumulativeCount += $result->audit_count;
                    $LocCumulativePercentage = round(($LoccumulativeCount / $totalAudits) * 100);
                    $LoccumulativePercentages[$loc][$result->sub_parameter_id] = [
                        'locauditcounts' => $result->audit_count,
                        'Loccumulative' => $LocCumulativePercentage,
                    ];
                }
            }

           // Location WIse pareto charts

            $LocSubCharts = [];

            foreach ($LoccumulativePercentages as $key => $value) {
                $Locsubparameters = [];
                $LocSubauditcount = [];
                $Loccumulatives = [];

                foreach ($value as $parameter => $P) {

                    $subparameter = QmSheetSubParameter::select('sub_parameter')->where('id', $parameter)->first();
                    if ($subparameter) {
                        $Locsubparameters[] = substr(preg_replace('/[^a-z0-9\s]/i', '', $subparameter->sub_parameter), 0, 17);
                        $LocSubauditcount[] = $P['locauditcounts'];
                        $Loccumulatives[] = $P['Loccumulative'];
                    }
                }

                $LocsubParametric =
                    "{'type':'bar','data':{'labels': " .
                    json_encode($Locsubparameters) .
                    ",'datasets':[{'type':'line','label':'cumulative','borderColor':'#ED7A2C','borderWidth':2,'fill':false,'data': [" .
                    implode(',', $Loccumulatives) .
                    "],datalabels: {display: true,color : '#040404',align: 'top',font: {size: 8,weight: 'bold'},formatter: function(value, context) {return value + '%';},},},{'type':'bar','label':'Audit Counts','backgroundColor':'#5B9BD5','data':" .
                    json_encode($LocSubauditcount) .
                    ",datalabels:{display: true,color : '#040404', backgroundColor: '#8FAADC' ,anchor:'start',align: 'end',offset: 0,font: {size: 8,weight: 'bold'}},}]},'options':{'responsive':true,'title':{'display':true,'text':' Combined Parametric Failure',backgroundColor: '#5B9BD5',fontSize: 8},legend: {labels: {fontSize:8,},'position':'bottom',},'tooltips':{'mode':'index','intersect':false},'scales':{'xAxes':[{gridLines: { lineWidth: 1, zeroLineWidth: 1 ,'drawTicks' : false , 'drawOnChartArea' : false,},ticks: {fontSize: 8}}],yAxes:[{type: 'linear',display: false,position: 'left',gridLines: {display : false,color: 'black', lineWidth: 0, drawBorder: false,drawTicks : false ,drawOnChartArea: false,},ticks: {display: false}}]}}}";

                $LocsubParametricChartImage = $this->generateChartImage($LocsubParametric);
                $LocSubCharts[$key] = $LocsubParametricChartImage;
            }

            $locCharts = [];
            foreach ($locData as $loc => $locvalues) {

                $LOCsaleQualityScore = $locData[$loc]['sale']['LOCSaleQualityScore'];
                $LOCsaleAudits = $locData[$loc]['sale']['LOCSaleaudits'];
                $LOCnonSaleQualityScore = $locData[$loc]['nonSale']['LOCNonSaleQualityScore'];
                $LOCNonsaleAudits = $locData[$loc]['nonSale']['LOCnonsaleAudits'];

                $LocsaleNonSale = "{type: 'pie',data: {datasets: [{data: [ $LOCsaleQualityScore , $LOCnonSaleQualityScore ],backgroundColor: ['rgb(255, 99, 132)','rgb(255, 159, 64)',], label: 'Dataset 1',datalabels: { formatter: (value, ctx) => {return value + '%'; }, backgroundColor: 'rgba(255, 255, 255, 0.8'},},{data: [$LOCsaleAudits , $LOCNonsaleAudits  ],backgroundColor: [ 'rgb(255, 99, 132)','rgb(255, 159, 64)',], label: 'Dataset 2',datalabels: {backgroundColor:'rgb(153, 102, 255)'},},], labels: ['Sale', 'NoneSale'], },options: {legend: { position: 'bottom', },}, }";

                $LocsaleNonSaleImage = $this->generateChartImage($LocsaleNonSale);
                $locCharts[$loc] = $LocsaleNonSaleImage;
            }

            // overall Location Trends
            // $overallLocations = $auditLocationOverall = $OveallLocationqualityScore  =  $weeks =  $OverallLocationsCharts = [];

            $OverallLocationsCharts = [];
            foreach ($weekDataLocation  as $location => $data) {

                $weeks = [];
                $auditLocationOverall = [];
                $OveallLocationqualityScore = [];
                $overallLocations[] = $location;


                foreach ($data as $key => $values) {
                    $weeks[] = 'Week ' . ($key + 1);
                    $auditLocationOverall[] = $values['auditLocationOverall'];
                    $OveallLocationqualityScore[] = $values['OveallLocationqualityScore'];
                }


                $auditLocationOverall = array_reverse($auditLocationOverall);
                $OveallLocationqualityScore = array_reverse($OveallLocationqualityScore);

                $overallLocationsTrends =
                    "{'type':'bar','data':{'labels':" .
                    json_encode($weeks) .
                    ",'datasets':[ {'type':'line','label':'Quality score','borderColor':'#ED7A2C','borderWidth':2,'fill':false,'data': [" .
                    implode(',',  $OveallLocationqualityScore) .
                    "],datalabels: {display: true,color : '#040404',align: 'top',font: {size: 8,weight: 'bold'},formatter: function(value, context) {return value + '%';},},},{'type':'bar','label':'Audit Counts','backgroundColor':'#5B9BD5','data':" .
                    json_encode($auditLocationOverall) .
                    ",datalabels:{display: true,color : '#040404',anchor: 'start',align: 'end',offset: 0,'backgroundColor':'#8FAADC',font: {size: 8,weight: 'bold'}},}]},'options':{'responsive':true,'title':{'display':true,'text':'',backgroundColor: '#5B9BD5',fontSize: 8},legend: {labels: {fontSize:8,},'position':'bottom',},'tooltips':{'mode':'index','intersect':false},'scales':{'xAxes':[{gridLines: { 'display': true, zeroLineWidth: 1, 'zeroLineColor' : 'black' , 'drawTicks' : false , 'drawOnChartArea' : false,},ticks: {fontSize: 8},}],yAxes:[{type: 'linear',display: false,position: 'left',gridLines: {display : false, zeroLineColor: 'black', zeroLineWidth: 8, drawTicks : false ,drawOnChartArea: false,},ticks: {display: false , beginAtZero: true}}]}}}";

                $overallLocationsTrendsConfig = $this->generateChartImage($overallLocationsTrends);
                $OverallLocationsCharts[$location] = $overallLocationsTrendsConfig;
            }




            $locations = $auditCounts =  $qualityScores =  $lobs   =  $tlCharts =  $SLACharts =   [];

            foreach ($locationData as $key => $location) {
                $locations[]  = $key;
                $lobs[] = $location['lob'];
                $qualityScores[] = $location['LOCQualityScore'];
                $auditCounts[] = $location['auditCounts'];

                $Tllocations = $tlNames =  $tlAuditCounts  =  $tlQualityScores =  $slaLocations = $slaName  = $slaAuditsCounts =  $slaQualityScores = [];

                foreach ($location['TLData'] as $tlName => $tlData) {
                    $tlLocations[] = $key;
                    $tlNames[] = $tlName;
                    $tlQualityScores[] = $tlData['TLQualtiyScore'];
                    $tlAuditCounts[] = $tlData['auditCountTLs'];
                }
                foreach ($location['SLAData'] as $slaNames => $slaData) {
                    $slaLocations = $key;
                    $slaName[] = $slaNames;
                    $slaAuditsCounts[] = $slaData['auditCountsSLA'];
                    $slaQualityScores[]  = $slaData['SLAQualityScore'];
                }

                $tlChart =
                    "{'type':'bar','data':{'labels':" .
                    json_encode($tlNames) .
                    ",'datasets':[ {'type':'line','label':'Quality score','borderColor':'#ED7A2C','borderWidth':2,'fill':false,'data': [" .
                    implode(',', $tlQualityScores) .
                    "],datalabels: {display: true,color : '#040404',align: 'top',font: {size: 8,weight: 'bold'},formatter: function(value, context) {return value + '%';},},},{'type':'bar','label':'Audit Counts','backgroundColor':'#5B9BD5','data':" .
                    json_encode($tlAuditCounts) .
                    ",datalabels:{display: true,color : '#040404', 'backgroundColor':'#8FAADC',anchor: 'start',align: 'end',offset: 0,font: {size: 8,weight: 'bold'}},}]},'options':{'responsive':true,'title':{'display':true,'text':'',backgroundColor: '#5B9BD5',fontSize: 8},legend: {labels: {fontSize:8,},'position':'bottom',},'tooltips':{'mode':'index','intersect':false},'scales':{'xAxes':[{gridLines: { 'display': true, zeroLineWidth: 1, 'zeroLineColor' : 'black' , 'drawTicks' : false , 'drawOnChartArea' : false,},ticks: {fontSize: 8},}],yAxes:[{type: 'linear',display: false,position: 'left',gridLines: {display : false, zeroLineColor: 'black', zeroLineWidth: 8, drawTicks : false ,drawOnChartArea: false,},ticks: {display: false , beginAtZero: true}}]}}}";


                $Tlchartconfig = $this->generateChartImage($tlChart);
                $tlCharts[$key] = $Tlchartconfig;


                $slaChart =
                    "{'type':'bar','data':{'labels':" .
                    json_encode($slaName) .
                    ",'datasets':[ {'type':'line','label':'Quality score','borderColor':'#ED7A2C','borderWidth':2,'fill':false,'data': [" .
                    implode(',', $slaQualityScores) .
                    "],datalabels: {display: true,color : '#040404',align: 'top',font: {size: 8,weight: 'bold'},formatter: function(value, context) {return value + '%';},},},{'type':'bar','label':'Audit Counts','backgroundColor':'#5B9BD5','data':" .
                    json_encode($slaAuditsCounts) .
                    ",datalabels:{display: true,color : '#040404', backgroundColor: '#8FAADC', anchor: 'start',align: 'end',offset: 0,font: {size: 8,weight: 'bold'}},}]},'options':{'responsive':true,'title':{'display':true,'text':' ',backgroundColor: '#5B9BD5',fontSize: 8},legend: {labels: {fontSize:8,},'position':'bottom',},'tooltips':{'mode':'index','intersect':false},'scales':{'xAxes':[{gridLines: { lineWidth: 1, zeroLineWidth: 1},ticks: {fontSize: 8},}],yAxes:[{type: 'linear',display: false,position: 'left',gridLines: {display : false, zeroLineColor: 'black', zeroLineWidth: 8, drawTicks : false ,drawOnChartArea: false,},ticks: {display: false , beginAtZero: true}}]}}}";

                $slachartconfig = $this->generateChartImage($slaChart);
                $SLACharts[$key] = $slachartconfig;
            };


            $auditCountsConfig =
                "{'type':'bar','data':{'labels':" .
                json_encode($locations) .
                ",'datasets':[ {'type':'line','label':'Quality score','borderColor':'#ED7A2C','borderWidth':2,'fill':false,'data': [" .
                implode(',', $qualityScores) .
                "],datalabels: {display: true,color : '#040404',backgroundColor : '#FFC000',align: 'top',font: {size: 8,weight: 'bold'},formatter: function(value, context) {return value + '%';},},},{'type':'bar','label':'Audit Counts','backgroundColor':'#5B9BD5','data':" .
                json_encode($auditCounts) .
                ",datalabels:{display: true,color : '#040404',backgroundColor: '#8FAADC',anchor: 'start',align: 'end',offset: 0,font: {size: 8,weight: 'bold'}},}]},'options':{'responsive':true,'title':{'display':true,'text':'', backgroundColor: '#5B9BD5',fontSize: 8},legend: {labels: {fontSize:8,},'position':'bottom',},'tooltips':{'mode':'index','intersect':false},'scales':{'xAxes':[{gridLines: { 'display': true, zeroLineWidth: 1, 'zeroLineColor' : 'black' , 'drawTicks' : false , 'drawOnChartArea' : false, },ticks: {fontSize: 8},}],yAxes:[{type: 'linear',display: false,position: 'left',gridLines: {display : false, zeroLineColor: 'black', zeroLineWidth: 8, drawTicks : false ,drawOnChartArea: false, },ticks: {display: false , beginAtZero: true}}]}}}";



            $auditCountChart = $this->generateChartImage($auditCountsConfig);


            // parametric failure chart
            $auditIds = Audit::with('raw_data')->whereHas('raw_data', function ($query) use ($lob) {
                $query->where('lob', $lob);
            })->whereBetween('audit_date', [$startDate, $endDate])->pluck('id');

            $auditResults = AuditResult::whereIn('audit_id', $auditIds)
                ->join('qm_sheet_sub_parameters', 'audit_results.sub_parameter_id', '=', 'qm_sheet_sub_parameters.id')
                ->where('audit_results.is_non_scoring', '!=', 1)
                ->where('audit_results.selected_option', 2)
                ->where('qm_sheet_sub_parameters.weight', '!=', 0)
                ->select('audit_results.sub_parameter_id', \DB::raw('COUNT(*) as audit_count'))
                ->groupBy('audit_results.sub_parameter_id')
                ->orderBy('audit_count', 'desc')
                ->get();
     

            // Calculate cumulative percentages
            $totalAudits = $auditResults->sum('audit_count');
            $cumulativeCount = 0;
            $cumulativePercentages = [];

            foreach ($auditResults as $result) {
                $cumulativeCount += $result->audit_count;
                $cumulativePercentage = round(($cumulativeCount / $totalAudits) * 100);
                $cumulativePercentages[$result->sub_parameter_id] = [
                    'auditcounts' => $result->audit_count,
                    'cumulative' => $cumulativePercentage,
                ];
            }

            // parametric chart

            $subparameters = [];
            $Subauditcount = [];
            $cumulatives = [];

            foreach ($cumulativePercentages as $key => $value) {
                $subparameter = QmSheetSubParameter::select('sub_parameter')->where('id', $key)->first();
                if ($subparameter) {
                    $subparameters[] = substr(preg_replace('/[^a-z0-9\s]/i', '', $subparameter->sub_parameter), 0, 17);
                    $Subauditcount[] = $value['auditcounts'];
                    $cumulatives[] = $value['cumulative'];
                }
            }


            $subParametric =
                "{'type':'bar','data':{'labels': " .
                json_encode($subparameters) .
                ",'datasets':[{'type':'line','label':'cumulative','borderColor':'#ED7A2C','borderWidth':2,'fill':false,'data': [" .
                implode(',', $cumulatives) .
                "],datalabels: {display: true,color : '#040404',align: 'top',font: {size: 8,weight: 'bold'},formatter: function(value, context) {return value + '%';},},},{'type':'bar','label':'Audit Counts','backgroundColor':'#5B9BD5','data':" .
                json_encode($Subauditcount) .
                ",datalabels:{display: true,color : '#040404',backgroundColor: '#8FAADC',anchor:'start',align: 'end',offset: 0,font: {size: 8,weight: 'bold'}},}]},'options':{'responsive':true,'title':{'display':true,'text':'  " . $lobs[0] . " Combined Parametric Failure',backgroundColor: '#5B9BD5',fontSize: 8},legend: {labels: {fontSize:8,},'position':'bottom',},'tooltips':{'mode':'index','intersect':false},'scales':{'xAxes':[{gridLines: { lineWidth: 1, zeroLineWidth: 1 ,'drawTicks' : false , 'drawOnChartArea' : false,},ticks: {fontSize: 8}}],yAxes:[{type: 'linear',display: false,position: 'left',gridLines: {display : false,color: 'black', lineWidth: 0, drawBorder: false,drawTicks : false ,drawOnChartArea: false,},ticks: {display: false}}]}}}";


            $subParametricChartImage = $this->generateChartImage($subParametric);

            // Calculate QC scores for sale and non-sale dispositions

            // Retrieve sale and non-sale audit data with common conditions
            // Sale and non-sale audit data
            $saleAuditData = Audit::with('raw_data')
                ->whereHas('raw_data', function (Builder $query) use ($lob) {
                    $query->where('client_id', 23)
                        ->where('status', 1)
                        ->where('lob', $lob)
                        ->where('disposition', 'like', 'sale');
                })
                ->whereBetween('audit_date', [$startDate, $endDate])
                ->get();
            $saleAuditCount = $saleAuditData->count();
            $saleWithfatal = $saleAuditData->sum('with_fatal_score_per');
            $saleWithoutfatal = $saleAuditData->sum('overall_score');


            $nonSaleAuditData = Audit::with('raw_data')
                ->whereHas('raw_data', function (Builder $query) use ($lob) {
                    $query->where('client_id', 23)
                        ->where('lob', $lob)
                        ->where('status', 1)
                        ->where('disposition', 'not like', 'sale');
                })
                ->whereBetween('audit_date', [$startDate, $endDate])
                ->get();

            $nonSaleAuditCount = $nonSaleAuditData->count();
            $nonSaleWithfatal = $nonSaleAuditData->sum('with_fatal_score_per');
            $nonSaleWithoutfatal = $nonSaleAuditData->sum('overall_score');
            // // Store QC scores

            $lobData['sale'] = [
                'Saleaudits' => $saleAuditCount,
                'SaleQualityScore' => ($saleWithoutfatal > 0) ? round($saleWithfatal / $saleWithoutfatal) : 0
            ];

            $lobData['nonSale'] = [
                'nonsaleAudits' =>  $nonSaleAuditCount,
                'NonSaleQualityScore' => ($nonSaleWithoutfatal > 0) ? round($nonSaleWithfatal / $nonSaleWithoutfatal) : 0
            ];

            $saleQualityScore = $lobData['sale']['SaleQualityScore'];
            $saleAudits = $lobData['sale']['Saleaudits'];
            $nonSaleQualityScore = $lobData['nonSale']['NonSaleQualityScore'];
            $NonsaleAudits = $lobData['nonSale']['nonsaleAudits'];

           
            $saleNonSale = "{type: 'pie',data: {datasets: [{data: [ $saleQualityScore , $nonSaleQualityScore ],backgroundColor: ['rgb(255, 99, 132)','rgb(255, 159, 64)',], label: 'Dataset 1',datalabels: { formatter: (value, ctx) => {return value + '%'; }, backgroundColor: 'rgba(255, 255, 255, 0.8'},},{data: [$saleAudits , $NonsaleAudits  ],backgroundColor: [ 'rgb(255, 99, 132)','rgb(255, 159, 64)',], label: 'Dataset 2',datalabels: {backgroundColor:'rgb(153, 102, 255)'},},], labels: ['Sale', 'NoneSale'], },options: {legend: { position: 'bottom', },}, }";



            $saleNonSaleImage = $this->generateChartImage($saleNonSale);
          

            $pdf = PDF::loadView('mail.Bfl_lobWIse_summary', [
                'locationData' => [$locationData],
                'weekData' =>  $weekData,
                'cumulativePercentages' => $cumulativePercentages,
                'subParametricChartImage' => $subParametricChartImage,
                'auditCountChart' =>  $auditCountChart,
                'saleNonSaleImage' =>  $saleNonSaleImage,
                'overallTrendImage' => $overallTrendImage,
                'tlCharts' =>  $tlCharts,
                'SLACharts' =>  $SLACharts,
                'locCharts' => $locCharts,
                'OverallLocationsCharts' => $OverallLocationsCharts,
                'LocSubCharts' => $LocSubCharts,

            ]);

            $pdfContent = $pdf->output();
            // $to_emails = ["palak.khandelwal@qdegrees.org"];
            // $to_emails = ["Rahul.adiwal@bajajfinserv.in","Sagar.Khajekar@bajajfinserv.in"];
            // $cc_emails = ["palak.khandelwal@qdegrees.org","Sumith.Kalaiselvan@qdegrees.com","NISHA.KRISHNA@qdegrees.com","Jasim.Ali@qdegrees.com","shubhda.sharma@qdegrees.com","kiran.ingulkar@qdegrees.org","SME.BFL@QDegrees.org","quality.bfl.pune@qdegrees.com"];

            Mail::to($to_emails)->cc($cc_emails)->send(new Bfl_lobWIse_summry($pdfContent,  [$locationData],  $cumulativePercentages));
        }
    }
    private function generateChartImage($config)
    {

        $httpClient = new Client();
        $response = $httpClient->get('https://quickchart.io/chart?&c=', [
            'query' => [
                'c' => $config,
                'w' => 500,
                'h' => 300,
            ],
        ]);

        return $response->getBody()->getContents();
    }
}
