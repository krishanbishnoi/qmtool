<?php

namespace App\Console\Commands;

use App\Audit;
use App\AuditResult;
use App\RawData;
use App\Mail\quality_dashboard;
use App\QmSheetSubParameter;
use Carbon\Carbon;
use DateTime;
use GuzzleHttp\Client;
use PDF;
use Illuminate\Support\Facades\Mail;

use Illuminate\Console\Command;

class QualityDashboard extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'quality:ScoresBFL';

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


        $currentYear = date('Y');
        $currentMonth = date('m');

        $months = [];
        $endMonths = [];

        for ($i = 1; $i <= $currentMonth; $i++) {
            $date = date('Y-m-01', strtotime("$currentYear-$i-01"));
            $months[] = $date;
            $lastDate = date('Y-m-t', strtotime($date));
            $endMonths[] = $lastDate;
        }

        // for ($i = 0; $i < $currentMonth; $i++) {
        //     $date = date('Y-m-01', strtotime(date('Y-m-01') . " -$i months"));
        //     $months[] = $date;
        //     $lastDate = date("Y-m-t", strtotime($date));
        //     $endMonths[] = $lastDate;
        // }
        // Month Wise

        $landingQs = [];
        $creditQs = [];
        $monthsNames = [];
        for ($i = 0; $i < count($endMonths); $i++) {
            $landingData = Audit::whereIn('process_id', [88, 101])->whereNotIn('partner_id', [113, 114])->where('client_id', 23)->whereDate('audit_date', '>=', $months[$i])->whereDate('audit_date', '<=', $endMonths[$i])->get();
            $overAllSum = $landingData->sum('overall_score');
            $withFatalSum = $landingData->sum('with_fatal_score_per');
            $landingQs[] = round(($withFatalSum / $overAllSum) * 100);
            $creditCard = Audit::where('process_id', 100)->whereNotIn('partner_id', [113, 114])->where('client_id', 23)->whereDate('audit_date', '>=', $months[$i])->whereDate('audit_date', '<=', $endMonths[$i])->get();
            $creditOverAllSum = $creditCard->sum('overall_score');
            $creditFatalSum = $creditCard->sum('with_fatal_score_per');
            $creditQs[] = round(($creditFatalSum / $creditOverAllSum) * 100);
            $monthsNames[] = date("F", strtotime($months[$i]));
        }

        $landingQs = array_reverse($landingQs);
        $creditQs = array_reverse($creditQs);
        // $monthsNames = array_reverse($monthsNames);

        $MonthwiseQcScores = "{'type':'bar','data':{'labels':" .
            json_encode($monthsNames) .
            ",'datasets':[
                    {'type':'bar','label':'Lending score%','backgroundColor':'rgb(31, 119, 180)','data': [" .
            implode(',', $landingQs) .
            "],datalabels: {display: true,color : '#040404', 'backgroundColor':'#FFFFFF',align: 'top',font: {size: 9,weight: 'bold'},formatter: function(value, context) {return value + '%';},} },
                    {'type':'bar','label':'CC%','backgroundColor':'rgb(255, 127, 14)','data':" .
            json_encode($creditQs) .
            ",datalabels:{display: true,color : '#040404','backgroundColor':'#FFFFFF',align: 'top',font: {size: 9,weight: 'bold'},formatter: function(value, context) {return value + '%';},}  } ]},'options':{'responsive':true,'title':{'display':true,'text':'',backgroundColor: '#5B9BD5',fontSize: 8},'legend': {labels: {fontSize:8,},'position':'bottom'}, 'tooltips':{'mode':'index','intersect':false}, 'scales':{ 'xAxes':[{barThickness: 35,gridLines: {'display': true,  zeroLineWidth: 1,'zeroLineColor' : 'black', 'drawTicks' : false,'drawOnChartArea' : false  },ticks: {fontSize: 10 , 'fontStyle': 'bold' ,color : '#000000' } }], 'yAxes':[{  type: 'linear', display: false, position: 'left', gridLines: { display : false, zeroLineColor: 'black',lineWidth: 0,drawBorder: false,drawTicks : false,drawOnChartArea: false },ticks: {  display: false,beginAtZero: true } }] }}  }";


        $MonthwiseQcScoresConfig = $this->generateChartImage($MonthwiseQcScores);

        $MonthwiseQcScoresLL =
            "{'type':'bar','data':{'labels':" .
            json_encode($monthsNames) .
            ",'datasets':[ {'type':'line','label':'Target %','borderColor':'#ED7A2C','borderWidth':2,'fill':false,'data': [80,80,80,80,80,80],datalabels: {display: true,color : '#040404',align: 'top',font: {size: 9,weight: 'bold'},formatter: function(value, context) {return value + '%';},},},{'type':'bar','label':'LL %','backgroundColor':'rgb(31, 119, 180)','data':" . json_encode($landingQs) .
            ",datalabels:{display: true,color : '#040404','backgroundColor':'#FFFFFF', anchor: 'start',align: 'top',offset: 0,font: {size: 9,weight: 'bold',},formatter: function(value, context) {return value + '%';},},}]},'options':{'responsive':true,'title':{'display':true,'text':'',backgroundColor: '#5B9BD5',fontSize: 8},legend: {labels: {fontSize:9,weight: 'bold'},'position':'bottom',},'tooltips':{'mode':'index','intersect':false},'scales':{'xAxes':[{barThickness: 35,gridLines: {'display': true, zeroLineWidth: 1, 'zeroLineColor' : 'black' , 'drawTicks' : false , 'drawOnChartArea' : false,},ticks: {fontSize: 11,'fontStyle': 'bold' ,color : '#000000',},}],yAxes:[{type: 'linear',display: false,position: 'left',gridLines: {display : false,zeroLineColor: 'black',lineWidth: 0, drawBorder: false,drawTicks : false ,drawOnChartArea: false,},ticks: {display: false , beginAtZero: true}}]}}}";

        $MonthwiseQcScoresConfigLL = $this->generateChartImage($MonthwiseQcScoresLL);

        $MonthwiseQcScoresCC =
            "{'type':'bar','data':{'labels':" .
            json_encode($monthsNames) .
            ",'datasets':[ {'type':'line','label':'Target %','borderColor':'#ED7A2C','borderWidth':2,'fill':false,'data': [80,80,80,80,80 , 80],datalabels: {display: true,color : '#040404',align: 'top',font: {size: 9,weight: 'bold'},formatter: function(value, context) {return value + '%';},},},{'type':'bar','label':'CC %','backgroundColor':'rgb(31, 119, 180)','data':" .
            json_encode($creditQs) .
            ",datalabels:{display: true,color : '#040404','backgroundColor':'#FFFFFF', anchor: 'start',align: 'top',offset: 0,font: {size: 9,weight: 'bold',},formatter: function(value, context) {return value + '%';}},}]},'options':{'responsive':true,'title':{'display':true,'text':'',backgroundColor: '#5B9BD5',fontSize: 8},legend: {labels: {fontSize:9,weight: 'bold'},'position':'bottom',},'tooltips':{'mode':'index','intersect':false},'scales':{'xAxes':[{barThickness: 35, gridLines: {'display': true, zeroLineWidth: 1, 'zeroLineColor' : 'black' , 'drawTicks' : false , 'drawOnChartArea' : false,},ticks: {fontSize: 11, 'fontStyle': 'bold' ,color : '#000000'},}],yAxes:[{type: 'linear',display: false,position: 'left',gridLines: {display : false,zeroLineColor: 'black',lineWidth: 0, drawBorder: false,drawTicks : false ,drawOnChartArea: false,},ticks: {display: false , beginAtZero: true}}]}}}";

        $MonthwiseQcScoresConfigCC = $this->generateChartImage($MonthwiseQcScoresCC);


        //Day Wise quality Score of process

        for ($i = 1; $i <= 2; $i++) {
            $date = date('Y-m-d', strtotime(date('Y-m-d') . " -$i days"));
            $days[] = $date;
        }
        

        for ($i = 0; $i < count($days); $i++) {
            $DaylandingData = Audit::whereIn('process_id', [88, 101])->whereNotIn('partner_id', [113, 114])->where('client_id', 23)->whereDate('audit_date', $days[$i])->get();
            $DayoverAllSum = $DaylandingData->sum('overall_score');
            $DaywithFatalSum = $DaylandingData->sum('with_fatal_score_per');
            $DaylandingQs[] =  $DayoverAllSum ? (round(($DaywithFatalSum / $DayoverAllSum) * 100)) : 0;
            $DaycreditCard = Audit::where('process_id', 100)->whereNotIn('partner_id', [113, 114])->where('client_id', 23)->whereDate('audit_date', $days[$i])->get();
            $DaycreditOverAllSum = $DaycreditCard->sum('overall_score');
            $DaycreditFatalSum = $DaycreditCard->sum('with_fatal_score_per');
            $DaycreditQs[] =  $DaycreditOverAllSum ? (round(($DaycreditFatalSum / $DaycreditOverAllSum) * 100)) : 0;
        }

        $DaylandingQs = array_reverse($DaylandingQs);
        $DaycreditQs = array_reverse($DaycreditQs);
        $days = array_reverse($days);



        $DayWiseScores = "{'type':'line','data':{'labels':" . json_encode($days) . ",'datasets':[{'label':'CC','borderColor':'#5B9BD5','backgroundColor':'#5B9BD5','borderWidth':2,'fill':false,'data':[" . implode(',', $DaycreditQs) . "],'datalabels':{'display':true,'color':'#040404','align':'top','font':{'size':9,'weight':'bold'},'formatter':function(value,context){return value + '%';}},},{'label':'Lending','borderColor':'#ED7A2C','backgroundColor':'#ED7A2C','borderWidth':2,'fill':false,'data':[" . implode(',', $DaylandingQs) . "],'datalabels':{'display':true,'color':'#040404','align':'bottom','font':{'size':9,'weight':'bold'},'formatter':function(value,context){return value + '%';}}}]},'options':{'responsive':true,'title':{'display':true,'text':'','fontSize':10},'legend':{'labels':{'fontSize':8},'position':'bottom'},'tooltips':{'mode':'index','intersect':false},'scales':{'xAxes':[{'gridLines':{'display':true,'zeroLineWidth':1,'zeroLineColor':'black','drawTicks':false,'drawOnChartArea':false},'ticks':{'fontSize':12, 'fontStyle': 'bold' ,color : '#000000'}}],'yAxes':[{'type':'linear','display':false,'position':'left','gridLines':{'display':false,'zeroLineColor':'black','lineWidth':0,'drawBorder':false,'drawTicks':false,'drawOnChartArea':false},'ticks':{'display':false,'beginAtZero':true,}}]}}}";

        $DayWiseScoresConfig = $this->generateChartImage($DayWiseScores);


        //Week Wise
        $WeeklandingQs = [];
        $WeekcreditQs = [];
        $weekLabels = [];

        $currentDate = Carbon::now();
        $firstWeekStartDate = $currentDate->copy()->startOfMonth()->startOfWeek();
        $currentWeekStartDate = $currentDate->copy()->startOfWeek();

        $count = 0;
        for ($weekStartDate = $firstWeekStartDate; $weekStartDate->lte($currentWeekStartDate); $weekStartDate->addWeek()) {
            $count++;
            $weekEndDate = $weekStartDate->copy()->endOfWeek();
            $weekLabels[] = 'Week ' . $count;
            $weeklandingData = Audit::whereIn('process_id', [88, 101])
                ->whereNotIn('partner_id', [113, 114])
              
                ->where('client_id', 23)
                ->whereBetween('audit_date', [$weekStartDate->toDateString(), $weekEndDate->toDateString()])
                ->get();
            $overAllSum = $weeklandingData->sum('overall_score');
            $withFatalSum = $weeklandingData->sum('with_fatal_score_per');
            $WeeklandingQs[] = $overAllSum ? round(($withFatalSum / $overAllSum) * 100) : 0;
            $WeekcreditCard = Audit::where('process_id', 100)
                ->whereNotIn('partner_id', [113, 114])
                
                ->where('client_id', 23)
                ->whereBetween('audit_date', [$weekStartDate->toDateString(), $weekEndDate->toDateString()])
                ->get();
            $creditOverAllSum = $WeekcreditCard->sum('overall_score');
            $creditFatalSum = $WeekcreditCard->sum('with_fatal_score_per');
            $WeekcreditQs[] = $creditOverAllSum ? round(($creditFatalSum / $creditOverAllSum) * 100) : 0;
        }

        $WeeklandingQs = array_reverse($WeeklandingQs);
        $WeekcreditQs = array_reverse($WeekcreditQs);
        // $weekLabels = array_reverse($weekLabels);
       


        $WeekwiseQcScores =
            "{'type':'bar','data':{'labels':" . json_encode($weekLabels) . ",'datasets':[ {'type':'bar','label':' Lending score%', 'backgroundColor':'rgb(31, 119, 180)','fill':false,'data': [" .
            implode(',', $WeeklandingQs) .
            "],datalabels: {display: true,color : '#040404','backgroundColor':'#FFFFFF',align: 'top',font: {size: 9,weight: 'bold'},formatter: function(value, context) {return value + '%';},},},{'type':'bar','label':'CC %','backgroundColor':'rgb(255, 127, 14)','data':" .
            json_encode($WeekcreditQs) .
            ",datalabels:{display: true,color : '#040404','backgroundColor':'#FFFFFF', align: 'top',offset: 0,font: {size: 9,weight: 'bold',},formatter: function(value, context) {return value + '%';},},}]},'options':{'responsive':true,'title':{'display':true,'text':' ',backgroundColor: '#5B9BD5',fontSize: 8},legend: {labels: {fontSize:9,weight: 'bold'},'position':'bottom',},'tooltips':{'mode':'index','intersect':false},'scales':{'xAxes':[{gridLines: {'display': true, zeroLineWidth: 1, 'zeroLineColor' : 'black' , 'drawTicks' : false , 'drawOnChartArea' : false,},ticks: {fontSize: 11, 'fontStyle': 'bold' ,color : '#000000'},}],yAxes:[{type: 'linear',display: false,position: 'left',gridLines: {display : false,zeroLineColor: 'black',lineWidth: 0, drawBorder: false,drawTicks : false ,drawOnChartArea: false,},ticks: {display: false , beginAtZero: true}}]}}}";
        $WeekwiseQcScoresConfig = $this->generateChartImage($WeekwiseQcScores);

        $WeekwiseQcScoresLL =
            "{type: 'bar',data: {labels: " . json_encode($weekLabels) . ",datasets: [{'label':'score%', backgroundColor: 'rgb(31, 119, 180)',data: [" .
            implode(',', $WeeklandingQs) .
            "],},],},options: {title: {display: true,text: '',},plugins: { datalabels: { anchor: 'center',align: 'center',color: '#666','backgroundColor':'#FFFFFF',font: {fontSize:9,weight: 'bold',},formatter: function(value, context) {return value + '%';},},},scales: { xAxes: [{ gridLines: {display: false,},ticks: {fontSize: 11, 'fontStyle': 'bold' ,color : '#000000'},}], yAxes: [{display: false,gridLines: {display: false, drawTicks : false ,drawOnChartArea: false,},ticks: {display: false , beginAtZero: true} }],},},}";

        $WeekwiseQcScoresLLConfig = $this->generateChartImage($WeekwiseQcScoresLL);

        $WeekwiseQcScoresCC =
            "{type: 'bar',data: {labels: " . json_encode($weekLabels) . ",datasets: [{'label':'score%', backgroundColor: 'rgb(31, 119, 180)',data: [" .
            implode(',', $WeekcreditQs) .
            "],},],},options: {title: {display: true,text: '',},plugins: { datalabels: { anchor: 'center',align: 'center',color: '#666','backgroundColor':'#FFFFFF',font: {fontSize:9,weight: 'bold',},formatter: function(value, context) {return value + '%';},},},    scales: { xAxes: [{ gridLines: {display: false,},ticks: {fontSize: 11, 'fontStyle': 'bold' ,color : '#000000'},}], yAxes: [{display: false,gridLines: {display: false, drawTicks : false ,drawOnChartArea: false,},ticks: {display: false , beginAtZero: true} }],},},}";

        $WeekwiseQcScoresCCConfig = $this->generateChartImage($WeekwiseQcScoresCC);


        $today = Carbon::now()->subDay();
        // Pareto

        $auditIds = Audit::whereIn('process_id', [88, 101])->whereNotIn('partner_id', [113, 114])->whereDate('audit_date', $today)->pluck('id');
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

        $auditIdsCC = Audit::where('process_id', 100)->whereNotIn('partner_id', [113, 114])->whereDate('audit_date', $today)->pluck('id');

        $auditResultsCC = AuditResult::whereIn('audit_id', $auditIdsCC)
            ->join('qm_sheet_sub_parameters', 'audit_results.sub_parameter_id', '=', 'qm_sheet_sub_parameters.id')
            ->where('audit_results.is_non_scoring', '!=', 1)
            ->where('audit_results.selected_option', 2)
            ->where('qm_sheet_sub_parameters.weight', '!=', 0)
            ->select('audit_results.sub_parameter_id', \DB::raw('COUNT(*) as audit_counts'))
            ->groupBy('audit_results.sub_parameter_id')
            ->orderBy('audit_counts', 'desc')
            ->get();

        // Calculate cumulative percentages
        $totalAuditsCC = $auditResultsCC->sum('audit_counts');
        $cumulativeCountCC = 0;
        $cumulativePercentagesCC = [];
        foreach ($auditResultsCC as $resultcc) {
            $cumulativeCountCC += $resultcc->audit_counts;
            $cumulativePercentageCC = round(($cumulativeCountCC / $totalAuditsCC) * 100);
            $cumulativePercentagesCC[$resultcc->sub_parameter_id] = [
                'auditcountscc' => $resultcc->audit_counts,
                'cumulativecc' => $cumulativePercentageCC,
            ];
        }

        $subparameters = $Subauditcount =  $cumulatives = [];
        foreach ($cumulativePercentages as $key => $value) {
            $subparameter = QmSheetSubParameter::select('sub_parameter')->where('id', $key)->first();
            if ($subparameter) {
                // $subparameters[] = substr(preg_replace('/[^a-z0-9\s]/i', '', $subparameter->sub_parameter), 0, 17);
                $cleanedSubparameter = preg_replace('/[^a-z0-9\s]/i', '', $subparameter->sub_parameter);
                $subparameters[] = mb_strlen($cleanedSubparameter) > 17
                    ? mb_substr($cleanedSubparameter, 0, 17) . '...'
                    : $cleanedSubparameter;
                $Subauditcount[] = $value['auditcounts'];
                $cumulatives[] = $value['cumulative'];
            }
        }

        $subparametersCC =   $SubauditcountCC =   $cumulativesCC = [];

        foreach ($cumulativePercentagesCC as $key => $value) {
            $ccsubparameter = QmSheetSubParameter::select('sub_parameter')->where('id', $key)->first();
            if ($subparameter) {
                // $subparametersCC[] = substr(preg_replace('/[^a-z0-9\s]/i', '', $ccsubparameter->sub_parameter), 0, 17);
                $cleanedSubparameter = preg_replace('/[^a-z0-9\s]/i', '', $ccsubparameter->sub_parameter);
                $subparametersCC[] = mb_strlen($cleanedSubparameter) > 17
                    ? mb_substr($cleanedSubparameter, 0, 17) . '...'
                    : $cleanedSubparameter;

                $SubauditcountCC[] = $value['auditcountscc'];
                $cumulativesCC[] = $value['cumulativecc'];
            }
        }

        $LLsubParametric =
            "{'type':'bar','data':{'labels': " .
            json_encode($subparameters) .
            ",'datasets':[{'type':'line','label':'CF%','borderColor':'#ED7A2C','borderWidth':2,'fill':false,'data': [" .
            implode(',', $cumulatives) .
            "],datalabels: {display: true,color : '#040404',align: 'top',font: {size: 8,weight: 'bold'},formatter: function(value, context) {return value + '%';},},},{'type':'bar','label':'Defects','backgroundColor':'rgb(31, 119, 180)','data':" .
            json_encode($Subauditcount) .
            ",datalabels:{display: true,color : '#040404', backgroundColor: '#FFFFFF' ,anchor:'start',align: 'end',offset: 0,font: {size: 7,weight: 'bold'}},}]},'options':{'responsive':true,'title':{'display':true,'text':'',backgroundColor: '#5B9BD5',fontSize: 9},legend: {labels: {fontSize:8,weight:'bold'},'position':'bottom',},'tooltips':{'mode':'index','intersect':false},'scales':{'xAxes':[{gridLines: { lineWidth: 1, zeroLineWidth: 1 ,'drawTicks' : false , 'drawOnChartArea' : false,},ticks: {fontSize: 7, 'fontStyle': 'bold' ,color : '#000000'}}],yAxes:[{type: 'linear',display: false,position: 'left',gridLines: {display : false,color: 'black', lineWidth: 0, drawBorder: false,drawTicks : false ,drawOnChartArea: false,},ticks: {display: false}}]}}}";

        $LLsubParametricConfig = $this->generateChartImage($LLsubParametric);


        $CCsubParametric =
            "{'type':'bar','data':{'labels': " .
            json_encode($subparametersCC) .
            ",'datasets':[{'type':'line','label':'CF%','borderColor':'#ED7A2C','borderWidth':2,'fill':false,'data': [" .
            implode(',', $cumulativesCC) .
            "],datalabels: {display: true,color : '#040404',align: 'top',font: {size: 8,weight: 'bold'},formatter: function(value, context) {return value + '%';},},},{'type':'bar','label':'Defects','backgroundColor':'rgb(31, 119, 180)','data':" .
            json_encode($SubauditcountCC) .
            ",datalabels:{display: true,color : '#040404', backgroundColor: '#FFFFFF' ,anchor:'start',align: 'end',offset: 0,font: {size: 7,weight: 'bold'}},}]},'options':{'responsive':true,'title':{'display':true,'text':'',backgroundColor: '#5B9BD5',fontSize: 9},legend: {labels: {weight: 'bold',fontSize:9},'position':'bottom',},'tooltips':{'mode':'index','intersect':false},'scales':{'xAxes':[{gridLines: { lineWidth: 1, zeroLineWidth: 1 ,'drawTicks' : false , 'drawOnChartArea' : false,},ticks: {fontSize: 7, 'fontStyle': 'bold' ,color : '#000000'}}],yAxes:[{type: 'linear',display: false,position: 'left',gridLines: {display : false,color: 'black', lineWidth: 0, drawBorder: false,drawTicks : false ,drawOnChartArea: false,},ticks: {display: false}}]}}}";

        $CCsubParametricConfig = $this->generateChartImage($CCsubParametric);
        //Lob Wise overall Socre

        $All_LOB = RawData::select('raw_data.lob as lob')
            ->where('client_id', 23)
            ->where('status', 1)
            ->whereNotIn('partner_id', [113, 114])
            ->whereDate('updated_at', $today)
            ->distinct()
            ->pluck('lob');

        $lobData =  $lobs =[];
        foreach ($All_LOB as $lob) {
            $raw_data = RawData::where('client_id', 23)->where('status', 1)->where('lob', $lob)->whereDate('updated_at', $today)->whereNotIn('partner_id', [113, 114])->pluck('id');
            $auditData = Audit::whereIn('raw_data_id', $raw_data)->get();
            $auditCounts = $auditData->count();
            if ($auditCounts > 0) {
                $LOCwithfatalscore = $auditData->sum('with_fatal_score_per');
                $LOCwithoutfatalscore = $auditData->sum('overall_score');
                $lobData[] = ($LOCwithoutfatalscore > 0) ?  round(($LOCwithfatalscore / $LOCwithoutfatalscore) * 100) : 0;
                $lobs[] = $lob;
            }
        }
        $LobWiseScores =
            "{type: 'bar',data: {labels: " . json_encode($lobs) . ",datasets: [{ label: 'Quality%',backgroundColor: 'rgb(31, 119, 180)',data: [" .
            implode(',', $lobData) .
            "],},],},options: {title: {display: true,text: '',},plugins: { datalabels: { anchor: 'center',align: 'center',color: '#666', 'backgroundColor':'#FFFFFF', font: {size: 8,weight: 'bold',},formatter: function(value, context) {return value + '%';},},},scales: { xAxes: [{ barThickness: 40 ,gridLines: {display: false,},ticks: {fontSize: 11, 'fontStyle': 'bold' ,color : '#000000'},}], yAxes: [{display: false,gridLines: {display: false, drawTicks : false ,drawOnChartArea: false,},ticks: {display: false , beginAtZero: true} }],},legend: {position: 'bottom'},},}";

        $LobWiseScoresConfig = $this->generateChartImage($LobWiseScores);


        $LocImages = [];
        $locations = RawData::where('client_id', 23)->whereIn('process_id', [88, 101])->whereNotIn('partner_id', [113, 114])->where('status', 1)->whereDate('updated_at', $today)->distinct()->pluck('location');
        foreach ($locations as $loc) {
            $all_lob = RawData::where('location', $loc)->where('status', 1)->where('client_id', 23)->whereNotIn('partner_id', [113, 114])->whereDate('updated_at', $today)->distinct()->pluck('lob');
            $loCData = [];
            $Lobs = []; 
            foreach ($all_lob  as $lob) {
                $raw_data = RawData::where('client_id', 23)->where('status', 1)->where('location', $loc)->where('lob', $lob)->whereDate('updated_at', $today)->whereNotIn('partner_id', [113, 114])->pluck('id');
                $LocauditData = Audit::whereIn('raw_data_id', $raw_data)->get();
                $auditCounts = $LocauditData->count();
                if ($auditCounts > 0) {
                    $LOCLOBwithfatalscore = $LocauditData->sum('with_fatal_score_per');
                    $LOCLOBwithoutfatalscore = $LocauditData->sum('overall_score');
                    $score = ($LOCLOBwithoutfatalscore > 0) ? round(($LOCLOBwithfatalscore / $LOCLOBwithoutfatalscore) * 100) : 0;
                    $loCData[] = $score;
                    $Lobs[] = $lob;
                }
            }
            if (!empty($loCData)) {
                $locLobWiseGraph =  "{type: 'bar',data: {labels: " . json_encode($Lobs) . ",datasets: [{ label: 'Quality%',backgroundColor: 'rgb(31, 119, 180)',data: [" . implode(',', $loCData) .
                    "],},],},options: {title: {display: true,text: '',},plugins: { datalabels: { anchor: 'center',align: 'center',color: '#666','backgroundColor':'#FFFFFF',font: {size: 9,weight: 'bold',},formatter: function(value, context) {return value + '%';},},},    scales: { xAxes: [{ barThickness: 40 , gridLines: {display: false,},ticks: {fontSize: 11, 'fontStyle': 'bold' ,color : '#000000'},}], yAxes: [{display: false,gridLines: {display: false, drawTicks : false ,drawOnChartArea: false,},ticks: {display: false , beginAtZero: true} }],},legend: {position: 'bottom'},},}";
            }
            $locLobWiseGraphImage = $this->generateChartImage($locLobWiseGraph);
            $LocImages[$loc] = $locLobWiseGraphImage;
        }
        $LocImagesCC = [];
        $locationsCC = RawData::where('client_id', 23)->where('process_id', 100)->whereNotIn('partner_id', [113, 114])->where('status', 1)->whereDate('updated_at', $today)->distinct()->pluck('location');
        foreach ($locationsCC as $loc) {
            $all_lobCC = RawData::where('location', $loc)->where('status', 1)->where('client_id', 23)->whereNotIn('partner_id', [113, 114])->whereDate('updated_at', $today)->distinct()->pluck('lob');
            $loCDataCC = [];
            $LobsCC = [];  
            foreach ($all_lobCC  as $lob) {
                $raw_dataCC = RawData::where('client_id', 23)->where('status', 1)->where('location', $loc)->where('lob', $lob)->whereDate('updated_at', $today)->whereNotIn('partner_id', [113, 114])->pluck('id');
                $LocauditDataCC = Audit::whereIn('raw_data_id', $raw_dataCC)->get();
                $auditCounts = $LocauditDataCC->count();
                if ($auditCounts > 0) {
                    $LOCLOBwithfatalscoreCC = $LocauditDataCC->sum('with_fatal_score_per');
                    $LOCLOBwithoutfatalscoreCC = $LocauditDataCC->sum('overall_score');
                    $scoreCC = ($LOCLOBwithoutfatalscoreCC > 0) ? round(($LOCLOBwithfatalscoreCC / $LOCLOBwithoutfatalscoreCC) * 100) : 0;
                    $loCDataCC[] = $scoreCC;
                    $LobsCC[] = $lob;
                }
            }
            if (!empty($loCData)) {
                $locLobWiseGraphCC =  "{type: 'bar',data: {labels: " . json_encode($LobsCC) . ",datasets: [{ label: 'Quality%',backgroundColor: 'rgb(31, 119, 180)',data: [" . implode(',', $loCDataCC) .
                    "],},],},options: {title: {display: true,text: '',},plugins: { datalabels: { anchor: 'center',align: 'center',color: '#666','backgroundColor':'#FFFFFF',font: {size: 9 ,weight: 'bold',},formatter: function(value, context) {return value + '%';},},}, scales: { xAxes: [{ barThickness: 40 ,gridLines: {display: false,},ticks: {fontSize: 11, 'fontStyle': 'bold' ,color : '#000000'},}], yAxes: [{display: false,gridLines: {display: false, drawTicks : false ,drawOnChartArea: false,},ticks: {display: false , beginAtZero: true} }],},legend: {position: 'bottom'},},}";
            }
            $locLobWiseGraphImageCC = $this->generateChartImage($locLobWiseGraphCC);
            $LocImagesCC[$loc] = $locLobWiseGraphImageCC;
        }



        $pdf = PDF::loadView('mail.BFL_QualityDashboard_report', [
            'MonthwiseQcScoresConfig' => $MonthwiseQcScoresConfig,
            'MonthwiseQcScoresConfigLL' => $MonthwiseQcScoresConfigLL,
            'MonthwiseQcScoresConfigCC' => $MonthwiseQcScoresConfigCC,
            'DayWiseScoresConfig' =>  $DayWiseScoresConfig,
            // 'CCdayWiseImage' =>  $CCdayWiseImage,
            // 'LendingdayWiseConfig' =>  $LendingdayWiseConfig,
            'WeekwiseQcScoresConfig' =>  $WeekwiseQcScoresConfig,
            'WeekwiseQcScoresLLConfig' =>  $WeekwiseQcScoresLLConfig,
            'WeekwiseQcScoresCCConfig' =>  $WeekwiseQcScoresCCConfig,
            'LLsubParametricConfig' =>  $LLsubParametricConfig,
            'CCsubParametricConfig' =>  $CCsubParametricConfig,
            'LobWiseScoresConfig' =>  $LobWiseScoresConfig,
            'LocImages' => $LocImages,
            'LocImagesCC' => $LocImagesCC,
            'creditQs' => $creditQs,
            'landingQs' => $landingQs,


        ]);

        $pdfContent = $pdf->output();

        $to_emails = ["Rahul.adiwal@bajajfinserv.in","Sagar.Khajekar@bajajfinserv.in"];
        $cc_emails = ["palak.khandelwal@qdegrees.org","Sumith.Kalaiselvan@qdegrees.com","NISHA.KRISHNA@qdegrees.com","Jasim.Ali@qdegrees.com","shubhda.sharma@qdegrees.com","kiran.ingulkar@qdegrees.org","quality.bfl.pune@qdegrees.com","SME.BFL@QDegrees.org",];

        // $to_emails = ["palak.khandelwal@qdegrees.org"];
        // $cc_emails = ["palak.khandelwal@qdegrees.org"];

        Mail::to($to_emails)->cc($cc_emails)->send(new quality_dashboard($pdfContent));
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
