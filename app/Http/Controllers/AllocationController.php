<?php

namespace App\Http\Controllers;

ini_set('memory_limit', '-1');

use App\Client;
use App\ClientsQtl;
use App\FailedRawDumpRow;
use App\FailedRawDumpSlot;
use App\Imports\RawDataImport;
use App\Imports\QaTargetImport;
use App\Imports\QaTargetImportMonth;
use App\Imports\QaTargetImportDaily;
use App\Partner;
use App\PartnerLocation;
use App\PartnersProcess;
use App\Process;
use App\QmSheet;
use App\RawData;
use App\RoleUser;
use App\DataPurge;
use App\User;
use Auth;
use Crypt;
use Excel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


use App\FailedMonthTargetRow;
use App\FailedMonthTarget;
use App\MonthTarget;
use App\Imports\MonthlyTargetImport;

use Carbon\Carbon;
use App\Audit;
use App\AuditResult;
use Illuminate\Database\Eloquent\Builder;


class AllocationController extends Controller
{
  public function qtl_qa()
  {
      view('allocation.qtl_qa');
  }

  public function testChart()
  {

    $startDate = Carbon::now()->subWeek()->startOfWeek();
    $endDate = Carbon::now()->subWeek()->endOfWeek();


    // $startDate = '2024-03-28 00:00:00';
    // $endDate = '2024-03-28 23:59:59';

    $All_LOB = RawData::select('raw_data.lob as lob')
      ->where('client_id', 23)
      ->where('status', 1)
      ->whereBetween('created_at', [$startDate, $endDate])
      ->distinct()
      ->pluck('lob');


    foreach ($All_LOB as $lob) {

      $locations = RawData::where('client_id', 23)
        ->where('lob', $lob)
        ->where('status', 1)
        ->whereBetween('created_at', [$startDate, $endDate])
        ->distinct()
        ->pluck('location');


      $locationData = [];


      foreach ($locations as $loc) {
        $locationData[$loc] = [
          'lob' => $lob,
          'LOCQualityScore' => 0,
          'auditCounts' => 0,
          'TLData' => [],
          'SLAData' => []
        ];

        //     // Location wise Audits data

        $auditData = Audit::select('id')
          ->with('raw_data')
          ->whereHas('raw_data', function (Builder $query) use ($lob, $loc) {
            $query->where('client_id', 23)
              ->where('lob', $lob)
              ->where('location', $loc)
              ->where('status', 1);
          })
          ->whereBetween('created_at', [$startDate, $endDate])
          ->get();

        $auditCounts = $auditData->count();
        $auditIds = $auditData->pluck('id');
        $count_of_audits[$loc] = $auditIds->count();

        if ($count_of_audits[$loc] > 0) {
          $LOCQualtiyScore = Audit::whereIn('id', $auditIds)->sum('with_fatal_score_per');
          $locationData[$loc]['auditCounts'] = $auditCounts;
          $locationData[$loc]['LOCQualityScore'] = ($count_of_audits[$loc] > 0) ? round($LOCQualtiyScore / $count_of_audits[$loc]) : 0;
        }

        //Tl wise

        $tls = RawData::where('client_id', 23)
          ->where('lob', $lob)
          ->where('location', $loc)
          ->whereBetween('created_at', [$startDate, $endDate])
          ->distinct()
          ->pluck('tl');


        foreach ($tls as $tl) {

          $auditDataTL = Audit::select('id')
            ->with('raw_data')
            ->whereHas('raw_data', function (Builder $query) use ($loc, $tl, $lob) {
              $query->where('client_id', 23)
                ->where('lob', $lob)
                ->where('location', $loc)
                ->where('tl', $tl)
                ->where('status', 1);
            })
            ->whereBetween('created_at', [$startDate, $endDate])
            ->get();

          $auditCountTLs =  $auditDataTL->count();
          $TLauditIds = $auditDataTL->pluck('id');

          if ($auditCountTLs) {
            $TLQualtiyScore = Audit::whereIn('id', $TLauditIds)->sum('with_fatal_score_per');
            $locationData[$loc]['TLData'][$tl] = [
              'auditCountTLs' => $auditCountTLs,
              'TLQualtiyScore' => ($auditCountTLs > 0) ? round($TLQualtiyScore / $auditCountTLs) : 0
            ];
          }
        }

        // SLA MANAGER WISE
        $slaManager = RawData::where('client_id', 23)
          ->where('lob', $lob)
          ->where('location', $loc)
          ->whereBetween('created_at', [$startDate, $endDate])
          ->distinct()
          ->pluck('info_4');

        foreach ($slaManager as $Slm) {

          $auditDataSLA = Audit::select('id')
            ->with('raw_data')
            ->whereHas('raw_data', function (Builder $query) use ($loc, $lob, $Slm) {
              $query
                ->where('client_id', 23)
                ->where('lob', $lob)
                ->where('location', $loc)
                ->where('info_4', $Slm)
                ->where('status', 1);
            })->whereBetween('created_at', [$startDate, $endDate])->get();

          $auditCountsSLA =  $auditDataSLA->count();
          $SLAauditIds = $auditDataSLA->pluck('id');
          if ($auditCountsSLA > 0) {
            $SLAQualityScore = Audit::whereIn('id', $SLAauditIds)->sum('with_fatal_score_per');
            $locationData[$loc]['SLAData'][$Slm] = [
              'auditCountsSLA' => $auditCountsSLA,
              'SLAQualityScore' => ($auditCountsSLA > 0) ? round($SLAQualityScore / $auditCountsSLA) : 0
            ];
          }
        }
      }

      $auditIds = Audit::with('raw_data')->whereHas('raw_data', function ($query) use ($lob) {
        $query->where('lob', $lob);
      })->whereBetween('created_at', [$startDate, $endDate])->pluck('id');

      $auditResults = AuditResult::whereIn('audit_id', $auditIds)
        ->where('selected_option', 2)
        ->select('sub_parameter_id', \DB::raw('COUNT(*) as audit_count'))
        ->groupBy('sub_parameter_id')
        ->orderBy('audit_count', 'desc') // Sort by audit count in descending order
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

      return view('mail.charts', ['locationData' => [$locationData], 'cumulativePercentages' => $cumulativePercentages]);
    }
  }

  public function get_company_qtl_list()
  {
    $all_qtl = User::where('company_id', Auth::user()->company_id)->whereHas('roles', function ($query) {
      $query->where('name', '=', 'qtl');
    })->pluck('name', 'id');

    return response()->json(['status' => 200, 'message' => "Success", 'data' => $all_qtl], 200);
  }
  public function get_qtl_team_list($qtl_id)
  {
    $data = User::where('company_id', Auth::user()->company_id)->whereHas('roles', function ($query) {
      $query->where('name', '=', 'qa');
    })->where('reporting_user_id', $qtl_id)->get();

    return response()->json(['status' => 200, 'message' => "Success", 'data' => $data], 200);
  }
  public function get_company_qa_list()
  {
    $all_qtl = User::where('company_id', Auth::user()->company_id)->whereHas('roles', function ($query) {
      $query->where('name', '=', 'qa');
    })->get();

    return response()->json(['status' => 200, 'message' => "Success", 'data' => $all_qtl], 200);
  }
  public function store_qtl_qa(Request $request)
  {
    foreach ($request->my_team as $key => $value) {

      DB::table('users')
        ->where('id', $value['id'])
        ->update(['reporting_user_id' => $request->selected_qtl]);
    }
    return response()->json(['status' => 200, 'message' => "Team updated successfully."], 200);
  }
  public function remove_qa_from_qtl_team($qa_id)
  {
    DB::table('users')
      ->where('id', $qa_id)
      ->update(['reporting_user_id' => null]);
    return response()->json(['status' => 200, 'message' => "Team updated successfully."], 200);
  }
  public function qa_sheet()
  {
    return view('allocation.qa_sheet');
  }
  public function get_company_client_list()
  {
    if (Auth::user()->hasRole('client')) {
      $all_client = Client::where('id', Auth::user()->client_detail->client_id)->pluck('name', 'id');
    } elseif (Auth::user()->id == 4169 || Auth::user()->id == 4171 || Auth::user()->id == 4203 || Auth::user()->id == 4202 || Auth::user()->id == 4173 || Auth::user()->id == 4201 || Auth::user()->id == 4170 || Auth::user()->id == 4168 || Auth::user()->id == 4174 || Auth::user()->id == 4172 || Auth::user()->id == 4200 ||  Auth::user()->id == 4177 || Auth::user()->id == 4175 || Auth::user()->id == 4198 ||  Auth::user()->id == 4196) {
      $id = [23];
      $all_client = Client::where('company_id', Auth::user()->company_id)->whereIn('id', $id)->pluck('name', 'id');
    } else {
      $all_client = Client::where('company_id', Auth::user()->company_id)->pluck('name', 'id');
    }




    return response()->json(['status' => 200, 'message' => "Success", 'data' => $all_client], 200);
  }
  public function get_qtls_by_client($client_id)
  {

    $temp = ClientsQtl::where('client_id', $client_id)->with('qtl_user')->get();
    $data = [];
    foreach ($temp as $key => $value) {
      $data[$value->qtl_user_id] = $value->qtl_user->name;
    }
    return response()->json(['status' => 200, 'message' => "Success", 'data' => $data], 200);
  }
  /* 
   public function get_sheets_by_client($client_id)
   {
     $all_sheet_list = QmSheet::where('client_id',$client_id)->with('process')->get();
     return response()->json(['status'=>200,'message'=>"Success",'data'=>$all_sheet_list], 200);
   } */

  public function upload_qa_target_month()
  {

    Excel::import(new QaTargetImportMonth, request()->file('file'));
    return redirect('qa_target/list')->with('success', 'QA Target updated successfully');
  }

  public function upload_qa_target_daily(Request $request)
  {
    Excel::import(new QaTargetImportDaily, request()->file('file'));
    return redirect('qa_daily_target/list')->with('success', 'QA Target updated successfully');
  }
  public function get_sheets_by_client($client_id)
  {
    $maxVersion = QmSheet::where('client_id', $client_id)->max('version');

    //$all_latest_sheet_list = QmSheet::where('client_id',$client_id)->where('version', $maxVersion)->pluck('id')->toArray();
    // $all_latest_sheet_list = QmSheet::where('client_id', $client_id)->pluck('id')->toArray();
    // $all_sheet_list = QmSheet::where('client_id', $client_id)->whereIn('id', $all_latest_sheet_list)->with('process')->get();


    if (Auth::user()->parent_client == 9) {

      $client_id = [9, 24, 26, 27]; // TATA , MoneyView , Dr. Agarwal
      $all_latest_sheet_list = QmSheet::whereIn('client_id', $client_id)->pluck('id')->toArray();
      $all_sheet_list = QmSheet::whereIn('client_id', $client_id)->whereIn('id', $all_latest_sheet_list)->with('process')->get();
    } else {


      $all_latest_sheet_list = QmSheet::where('client_id', $client_id)->pluck('id')->toArray();
      $all_sheet_list = QmSheet::where('client_id', $client_id)->whereIn('id', $all_latest_sheet_list)->with('process')->get();
    }
    return response()->json(['status' => 200, 'message' => "Success", 'data' => $all_sheet_list], 200);
  }



  public function get_sheets_by_client_of_process($client_id, $process_id)
  {


    if (Auth::user()->parent_client == 9) {
      $client_id = [9, 24, 26, 27]; // TATA , MoneyView , Dr. Agarwal , Lending Kart
      $all_latest_sheet_list = QmSheet::whereIn('client_id', $client_id)->pluck('id')->toArray();
      $all_sheet_list = QmSheet::whereIn('client_id', $client_id)->whereIn('id', $all_latest_sheet_list)->with('process')->get();
    } elseif (Auth::user()->id == 4169 || Auth::user()->id == 4171 || Auth::user()->id == 4203 || Auth::user()->id == 4202 || Auth::user()->id == 4173 || Auth::user()->id == 4201 || Auth::user()->id == 4170 || Auth::user()->id == 4168 || Auth::user()->id == 4174 || Auth::user()->id == 4172 || Auth::user()->id == 4200 ||  Auth::user()->id == 4177 || Auth::user()->id == 4175 || Auth::user()->id == 4198 ||  Auth::user()->id == 4196) {


      $maxVersion = QmSheet::where('process_id', $process_id)->max('version');
      $all_latest_sheet_list = QmSheet::where('version', $maxVersion)->where('process_id', $process_id)->pluck('id')->toArray();
      $all_sheet_list = QmSheet::where('client_id', $client_id)->whereIn('id', $all_latest_sheet_list)->with('process')->get();
    } else {
      $maxVersion = QmSheet::where('client_id', $client_id)->where('process_id', $process_id)->max('version');
      $all_latest_sheet_list = QmSheet::where('client_id', $client_id)->where('version', $maxVersion)->pluck('id')->toArray();
      $all_sheet_list = QmSheet::where('client_id', $client_id)->whereIn('id', $all_latest_sheet_list)->with('process')->get();
    }
    return response()->json(['status' => 200, 'message' => "Success", 'data' => $all_sheet_list], 200);
  }


  public function get_qtl_team($qtl_id)
  {
    $all_qtl_team = User::where('company_id', Auth::user()->company_id)->whereHas('roles', function ($query) {
      $query->where('name', '=', 'qa');
    })->where('reporting_user_id', $qtl_id)->get();
    return response()->json(['status' => 200, 'message' => "Success", 'data' => $all_qtl_team], 200);
  }
  /* public function store_qa_sheet(Request $request)
   {
      foreach ($request->my_team as $key => $value) {
      DB::table('users')
            ->where('id', $value['id'])
            ->update(['assigned_sheet_id' => $request->sheet_id]);
        }
        return response()->json(['status'=>200,'message'=>"QM Sheet alloted successfully."], 200); 
   } */

  public function store_qa_sheet(Request $request)
  {
    $process_ids = array_map(function ($prod) {
      return substr($prod, strpos($prod, "-") + 1);
    }, $request->sheet_id);

    $json_pro_ids = json_encode($process_ids);

    $array_size = sizeof($process_ids);
    $last_process_id = $process_ids[$array_size - 1];

    $sheet_details = QmSheet::where('process_id', $last_process_id)->orderby('id', 'desc')->first();

    $sheet_ids = array_map(function ($prod) {
      return substr($prod, 0, strpos($prod, '-'));
    }, $request->sheet_id);


    foreach ($request->my_team as $key => $value) {
      /*DB::table('users')
            ->where('id', $value['id'])
            ->update(['assigned_sheet_id' => $request->sheet_id]);
        }*/

      $user_update = DB::table('users')
        ->where('id', $value['id'])
        ->update(['assigned_process_ids' => $json_pro_ids]);

      $user_update_sheet = DB::table('users')
        ->where('id', $value['id'])
        ->update(['assigned_sheet_id' => $sheet_details->id]);
    }

    return response()->json(['status' => 200, 'message' => "QM Sheet alloted successfully."], 200);
  }

  public function get_qm_sheet_associated_qa($sheet_id)
  {
    $associated_qa = User::where('company_id', Auth::user()->company_id)->whereHas('roles', function ($query) {
      $query->where('name', '=', 'qa');
    })->where('assigned_sheet_id', $sheet_id)->get();
    return response()->json(['status' => 200, 'message' => "Success", 'data' => $associated_qa], 200);
  }

  public function pendingList($batch_id)
  {
    $data = RawData::where(['batch_id' => Crypt::decrypt($batch_id), 'status' => 0, 'visiblity' => 1])->get();
    $allQa = RoleUser::with('getUser')->where('role_id', 5)->get();
    //dd($allQA); die;
    $batch_id = Crypt::decrypt($batch_id);
    return view('allocation.pending_list', compact('data', 'allQa', 'batch_id'));
  }

  public function reassign(Request $request)
  {
    //echo "<pre>"; print_r($request->all()); die;
    $r = RawData::find($request->raw_data_id);
    $r->qa_id = $request->qa_id;
    $r->save();
    return redirect('allocation/pendingList/' . Crypt::encrypt($request->batch_id))->with('success', 'QA Re-assigned successfully');
  }

  public function dump_uploader()
  {
    return view('allocation.dump_uploader');
  }
  public function month_target_uploader()
  {
    return view('allocation.month_target_uploader');
  }

  public function upload_month_target(Request $request)
  {


    $batch = new FailedMonthTarget;

    // $batch->date = $request->dump_date;
    $batch->company_id = $request->company_id;
    $batch->client_id = $request->client_id;
    $batch->partner_id = $request->partner_id;
    $batch->process_id = $request->process_id;
    $batch->uploaded_by = Auth::user()->id;
    $batch->month_of_target = $request->dump_date;
    $request->raw_data_file->store("month_target_file");

    $batch->file_name = $request->raw_data_file->hashName();

    $batch->save();
    if ($batch->id) {

      $data = Excel::import(new MonthlyTargetImport([
        'dump_date' => $request->dump_date,
        'company_id' => $request->company_id,
        'client_id' => $request->client_id,
        'partner_id' => $request->partner_id,
        'process_id' => $request->process_id,
        'batch_id' => $batch->id,
        'lob' => $request->lob
      ]), $request->raw_data_file);
    }

    // return redirect('allocation/upload_report/'.$batch->id)->with('success', 'Raw data uploaded successfully, please falied record status.');
    //return redirect('allocation/list_month_target/')->with('success', 'Target data uploaded successfully.');    
    return redirect('allocation/upload_target_report/' . Crypt::encrypt($batch->id))->with('success', 'Target data uploaded successfully, Following list could not be uploaded.');
  }

  public function upload_target_report($batch_id)
  {
    $data = FailedMonthTargetRow::where('slot_batch_id', Crypt::decrypt($batch_id))->get();
    return view('allocation.upload_target_report', compact('data'));
  }
  public function partner_target_full($batch_id)
  {
    $data = MonthTarget::where('slot_batch_id', Crypt::decrypt($batch_id))->get();
    return view('allocation.partner_target_full', compact('data'));
  }

  public function monthly_partner_targets()
  {
    $data = FailedMonthTarget::with('client', 'process', 'partner', 'uploader', 'audit_cycle')->orderBy('id', 'desc')->limit(50)->get();

    return view('allocation.monthly_partner_targets', compact('data'));
  }

  public function list_month_target()
  {
    $data = MonthTarget::select('partners.name', 'auditcycles.name as cycle_name', 'month_targets.*')
      ->join('partners', 'month_targets.partner_id', '=', 'partners.id')
      ->join('auditcycles', 'month_targets.month_of_target', '=', 'auditcycles.id')
      ->get();
    /*  echo "<pre>";
    print_r($data);
    dd(); */
    return view('allocation.month_target_list', compact('data'));
  }

  public function get_client_partner($client_id)
  {
    // if (Auth::user()->parent_client == 9) {
    $data = Partner::where('client_id', $client_id)->pluck('name', 'id');
    // } else {
    //     $data = Partner::pluck('name', 'id');
    // }
    // $data = Partner::where('client_id', $client_id)->pluck('name', 'id');

    if (Auth::user()->id == 4169 || Auth::user()->id == 4171 || Auth::user()->id == 4203 || Auth::user()->id == 4202 || Auth::user()->id == 4173 || Auth::user()->id == 4201 || Auth::user()->id == 4170 || Auth::user()->id == 4168 || Auth::user()->id == 4174 || Auth::user()->id == 4172 || Auth::user()->id == 4200 ||  Auth::user()->id == 4177 || Auth::user()->id == 4175 || Auth::user()->id == 4198 ||  Auth::user()->id == 4196) {
      $data = Partner::where('id', 153)->pluck('name', 'id');
    } else {
      $data = Partner::where('client_id', $client_id)->pluck('name', 'id');
    }

    return response()->json(['status' => 200, 'message' => "Success", 'data' => $data], 200);
  }
  public function qm_sheet_client_partner($client_id)
  {
    if (Auth::user()->parent_client == 9) {
      $partners = Partner::where('client_id', $client_id)->pluck('id')->toArray();
      $processId = PartnersProcess::whereIn('partner_id', $partners)->pluck('process_id')->toArray();
      $items = Process::whereIn('id', $processId)->pluck('name', 'id');
    } else {
      $items = Process::where('company_id', Auth::user()->company_id)->pluck('name', 'id');
    }
    $html = "";
    foreach ($items as $key => $item) {
      $html .= '<option value=' . $key . '>' . $item . '</option>';
    }
    return response()->json(['status' => 200, 'message' => 'Success', 'data' => $html], 200);
  }

  public function get_partners_process($partner_id)
  {
    $temp = PartnersProcess::where('partner_id', $partner_id)->with('process')->get();
    $data = [];
    foreach ($temp as $key => $value) {
      $data[$value->process_id] = $value->process->name;
    }
    return response()->json(['status' => 200, 'message' => "Success", 'data' => $data], 200);
  }
  public function upload_raw_data_dump(Request $request)
  {
    // $batch = new FailedRawDumpSlot;
    // //$batch->date = (!empty($request->dump_date))? date("Y-m-d", strtotime($request->dump_date)):date("Y-m-d");
    // $batch->date = date("Y-m-d", strtotime($request->dump_date));
    // $batch->company_id = $request->company_id;
    // $batch->client_id = $request->client_id;
    // $batch->partner_id = $request->partner_id;
    // $batch->process_id = $request->process_id;
    // $batch->uploader_id = Auth::user()->id;
    // $request->raw_data_file->store("raw_data_dump_file");
    // $batch->file_name = $request->raw_data_file->hashName();
    // $batch->save();
    // if ($batch->id) {
    //   $data = Excel::import(new RawDataImport([
    //     'dump_date' => date("Y-m-d", strtotime($request->dump_date)),
    //     'company_id' => $request->company_id,
    //     'client_id' => $request->client_id,
    //     'partner_id' => $request->partner_id,
    //     'process_id' => $request->process_id,
    //     'batch_id' => $batch->id
    //   ]), $request->raw_data_file);
    // }
    $batch = new FailedRawDumpSlot;
    //$batch->date = (!empty($request->dump_date))? date("Y-m-d", strtotime($request->dump_date)):date("Y-m-d");
    $batch->date = date("Y-m-d", strtotime($request->dump_date));
    $batch->company_id = $request->company_id;
    $batch->client_id = $request->client_id;
    $batch->partner_id = $request->partner_id;
    $batch->process_id = $request->process_id;
    $batch->uploader_id = Auth::user()->id;
    if ($request->process_id != 138) {
      $batch->file_name = 'test';
      // $request->raw_data_file->store("raw_data_dump_file");
      // $batch->file_name = $request->raw_data_file->hashName();
    } else {
      $batch->file_name = 'test';
    }

    $batch->save();
    if ($batch->id) {
      $data = Excel::import(new RawDataImport([
        'dump_date' => date("Y-m-d", strtotime($request->dump_date)),
        'company_id' => $request->company_id,
        'client_id' => $request->client_id,
        'partner_id' => $request->partner_id,
        'process_id' => $request->process_id,
        'batch_id' => $batch->id
      ]), $request->raw_data_file);
    }

    return redirect('allocation/upload_report/' . $batch->id)->with('success', 'Raw data uploaded successfully, please falied record status.');
  }
  public function qa_agent()
  {
    return view('allocation.qa_agent');
  }
  public function get_available_agents_to_allocate(Request $request)
  {
    $data = RawData::where('status', 0)->where('dump_date', date("Y-m-d", strtotime($request->dump_date)))->where('client_id', $request->client_id)->where('partner_id', $request->partner_id)->where('qa_id', null)->groupBy('agent_name')->get();
    return response()->json(['status' => 200, 'message' => "Success", 'data' => $data->toArray()], 200);
  }
  public function update_qa_agent(Request $request)
  {
    foreach ($request->selected_agent_list as $key => $value) {
      DB::table('raw_data')
        ->where('dump_date', date("Y-m-d", strtotime($request->dump_date)))
        ->where('client_id', $request->client_id)
        ->where('partner_id', $request->partner_id)
        ->where('agent_name', $value['agent_name'])
        ->update(['qtl_id' => $request->selected_qtl, 'qa_id' => $request->selected_qa]);
    }
    return response()->json(['status' => 200, 'message' => "Agent alloted successfully."], 200);
  }
  public function get_alloted_partners_agent_by_qa(Request $request)
  {
    $data = RawData::where('status', 0)->where('client_id', $request->client_id)->where('partner_id', $request->partner_id)->where('qa_id', $request->qa_id)->groupBy('agent_name')->get();
    return response()->json(['status' => 200, 'message' => "Success", 'data' => $data->toArray()], 200);
  }

  public function remove_agent_from_qa_team(Request $request)
  {
    DB::table('raw_data')
      ->where('dump_date', date("Y-m-d", strtotime($request->dump_date)))
      ->where('client_id', $request->client_id)
      ->where('partner_id', $request->partner_id)
      ->where('qa_id', $request->qa_id)
      ->where('agent_name', $request->agent_name)
      ->update(['qtl_id' => null, 'qa_id' => null]);
    return response()->json(['status' => 200, 'message' => "Agent detached successfully."], 200);
  }
  public function get_partners_location($partner_id)
  {
    $all_partner_locations = [];
    $all_partner_locations_temp = PartnerLocation::where('partner_id', $partner_id)->with('location_detail')->get();
    foreach ($all_partner_locations_temp as $key => $value) {
      $all_partner_locations[$value->location_id] = $value->location_detail->name;
    }
    return response()->json(['status' => 200, 'message' => "Success", 'data' => $all_partner_locations], 200);
  }
  public function upload_report($batch_id)
  {
    $data = FailedRawDumpRow::where('failed_raw_dump_slot_id', $batch_id)->get();
    return view('allocation.upload_report', compact('data'));
  }

  public function qa_target()
  {
    return view('allocation.qa_target');
  }

  public function upload_qa_target()
  {
    Excel::import(new QaTargetImport, request()->file('file'));
    return redirect('allocation/qa_target')->with('success', 'Target updated successfully');
  }

  public function batch_data()
  {
    $data = FailedRawDumpSlot::with(['client', 'process', 'partner', 'uploader'])->orderby('id', 'desc')->limit(100)->get();
    return view('allocation.raw_data_batch', compact('data'));
  }

  public function change_visiblity_status($batch_id, $status)
  {
    $raw_records = RawData::where('batch_id', Crypt::decrypt($batch_id))->get();
    foreach ($raw_records as $key => $value) {
      $value->visiblity = $status;
      $value->save();
    }
    $batch = FailedRawDumpSlot::find(Crypt::decrypt($batch_id));
    $batch->visiblity = $status;
    $batch->save();
    return redirect('allocation/raw_data_batch')->with('success', 'Batch status updated successfully');
  }

  public function destroy($id)
  {
    FailedRawDumpSlot::find(Crypt::decrypt($id))->delete();
    RawData::where('batch_id', Crypt::decrypt($id))->where('status', 0)->delete();
    return redirect('allocation/raw_data_batch')->with('success', 'Raw Data Batch deleted successfully.');
  }

  public function data_purge()
  {
    $data = DataPurge::all();
    return view('allocation.data_purge', compact('data'));
  }

  public function reassign_multiple(Request $request)
  {
    /* echo $_POST['raw_data'][0]; */
    $allocate_to = $request->qa_id;
    $batch_id = $request->batch_id;
    /* echo $allocate_to;
    dd(); */
    foreach ($_POST['raw_data'] as $key => $value) {
      /* echo $key;
      echo "Hiii";
      echo $value; */
      if ($value == 1 and $key != 0 and $allocate_to != 0) {
        $raw_data = RawData::find($key);
        if ($raw_data->status == 0) {
          $raw_data->qa_id = $allocate_to;
          $raw_data->save();
        }
      }
    }

    /* echo $batch_id;
    dd(); */
    $data = RawData::where(['batch_id' => $batch_id, 'status' => 0, 'visiblity' => 1])->get();
    $allQa = RoleUser::with('getUser')->where('role_id', 5)->get();
    //dd($allQA); die;
    return view('allocation.pending_list', compact('data', 'allQa', 'batch_id'));
  }

  public function reassign_search(Request $request)
  {
    /* echo $_POST['raw_data'][0]; */
    $search = $request->search;
    $search  = "%" . $search . "%";
    $batch_id = $request->batch_id;

    /* echo $batch_id;
    dd(); */

    $data = RawData::select('raw_data.*')->join('users', 'raw_data.qa_id', '=', 'users.id')
      ->where(['batch_id' => $batch_id, 'raw_data.status' => 0, 'raw_data.visiblity' => 1])
      ->where('users.name', 'like', $search)
      ->get();
    $allQa = RoleUser::with('getUser')->where('role_id', 5)->get();
    //dd($allQA); die;
    return view('allocation.pending_list', compact('data', 'allQa', 'batch_id'));
  }

  public function clientQa(Request $request)
  {
    try {
      $token = $request->header('Authorization');
      $userDetails = User::where("remember_token", $token)->firstOrFail();
      if (!$token) {
        $data = array('status' => 0, 'message' => 'Validation Errors', 'data' => 'Token not found');
        return response(json_encode($data), 200);
      }
      if (!$userDetails) {
        $data = array('status' => 0, 'message' => 'Validation Errors', 'data' => 'User not found');
        return response(json_encode($data), 200);
      }
      if ($token != $userDetails->remember_token) {
        $data = array('status' => 0, 'message' => 'Validation Errors', 'data' => 'Please Login again');
        return response(json_encode($data), 200);
      }
      $validator = Validator::make($request->all(), [
        'user_id' => 'required',
      ]);
      if ($validator->fails()) {
        $data = array('status' => 0, 'message' => 'Validation Errors', 'data' => $validator->errors());
        return response(json_encode($data), 200);
      } else {
        dd("hsfdddd");
        $lastDate = date('Y-m-d', strtotime("-1 days"));
        // $rawData = RawData::whereHas('audit', function ($query) use ($lastDate) {$query->whereDate('audit_date', $lastDate);
        // })->where('status', 1)->select('id','client_id','qa_id')->with('client')->limit(50)->get();
        $rawClientDatas = Client::pluck('id');
        $data = [];
        foreach ($rawClientDatas as $key => $rawClientData) {
          $qaIds = RawData::where('client_id', $rawClientData)->whereHas('audit', function ($query) use ($lastDate) {
            $query->whereDate('audit_date', $lastDate);
          })->distinct('qa_id')->pluck('qa_id')->toArray();
          foreach ($qaIds as $key2 => $qaId) {
            $clientInfo = Client::find($rawClientData);
            $qaInfo = User::find($qaId);
            $data[$key2]['qaName'] = $qaInfo->name;
            $data[$key2]['clientName'] =  $clientInfo->name;
            $data[$key2]['totalAudit'] = RawData::where('client_id', $rawClientData)->where('qa_id', $qaId)->count();
            $data[$key2]['totalDoneAudit'] = RawData::where('client_id', $rawClientData)->where('qa_id', $qaId)->where('status', 1)->count();
          }
        }
        return response()->json(['status' => true, 'message' => 'Audit records.', 'data' => $data]);
      }
    } catch (\Exception $th) {
      return response()->json(['status' => false, 'message' => $th->getMessage(), 'data' => []]);
    }
  }


  public function updated_lob(Request $request)
  {

    $request->validate([
      'old_lob' => 'required|string',  // The LOB to be replaced
      'new_lob' => 'required|string',  // The new LOB value
    ]);
    $lobExists = RawData::where('lob', $request->old_lob)->exists();


    // $lob = User::where('email',$request->lob)->first();

    if ($lobExists) {
      RawData::where('client_id', 23)->where('lob', $request->old_lob)
        ->update(['lob' => $request->new_lob]);

      return response()->json([
        'status' => 200,
        'message' => "LOB updated successfully from '{$request->old_lob}' to '{$request->new_lob}'"
      ], 200);
    } else {

      return response()->json([
        'status' => 404,
        'message' => "LOB '{$request->old_lob}' not found"
      ], 404);
    }
  }

  public function akhil()
  {
    $rawDatas = RawData::whereIn('id', [
      59336154,
      59336153,
      59336152,
      59336151,
      59336150,
      59336149,
      59336148,
      59336147,
      59336146,
      59336145,
      59336144,
      59336143,
      59336142,
      59336141,
      59336140,
      59336139,
      59336138,
      59336137,
      59336136,
      59336135,
      59336134,
      59336133,
      59336132,
      59336131,
      59336130,
      59336129,
      59336128,
      59336127,
      59336126,
      59336125,
      59336124,
      59336123,
      59336122,
      59336121,
      59336120,
      59336119,
      59336118,
      59336117,
      59336116,
      59336115,
      59336114,
      59336113,
      59336112,
      59336111,
      59336110,
      59336109,
      59336108,
      59336107,
      59336106,
      59336105,
      59336104,
      59336103,
      59336102,
      59336101,
      59336100,
      59336099,
      59336098,
      59336097,
      59336096,
      59336095,
      59336094,
      59336093,
      59336092,
      59336091,
      59336090,
      59336089,
      59336088,
      59336087,
      59336086,
      59336085,
      59336084,
      59336083,
      59336082,
      59336081,
      59336080,
      59336079,
      59336078,
      59336077,
      59336076,
      59336075,
      59336074,
      59336073,
      59336072,
      59336071,
      59336070,
      59336069,
      59336068,
      59336067,
      59336066,
      59336065,
      59336064,
      59336063,
      59336062,
      59336061,
      59336060,
      59336059,
      59336058,
      59336057,
      59336056,
      59336055,
      59336054,
      59336053,
      59336052,
      59336051,
      59336050,
      59336049,
      59336048,
      59336047,
      59336046,
      59336045,
      59336044,
      59336043,
      59336042,
      59336041,
      59336040,
      59336039,
      59336038,
      59336037,
      59336036,
      59336035,
      59336034,
      59336033,
      59336032,
      59336031,
      59336030,
      59336029,
      59336028,
      59336027,
      59336026,
      59336025,
      59336024,
      59336023,
      59336022,
      59336021,
      59336020,
      59336019,
      59336018,
      59336017,
      59336016,
      59336015,
      59336014,
      59336013,
      59336012,
      59336011,
      59336010,
      59336009,
      59336008,
      59336007,
      59336006,
      59336005,
      59336004,
      59336003,
      59336002,
      59336001,
      59336000,
      59335999,
      59335998,
      59335997,
      59335996,
      59335995,
      59335994,
      59335993,
      59335992,
      59335991,
      59335990,
      59335989,
      59335988,
      59335987,
      59335986,
      59335985,
      59335984,
      59335983,
      59335982,
      59335981,
      59335980,
      59335979,
      59335978,
      59335977,
      59335976,
      59335975,
      59335974,
      59335973,
      59335972,
      59335971,
      59335970,
      59335969,
      59335968,
      59335967,
      59335966,
      59335965,
      59335964,
      59335963,
      59335962,
      59335961,
      59335960,
      59335959,
      59335958,
      59335957,
      59335956,
      59335955,
      59335954,
      59335953,
      59335952,
      59335951,
      59335950,
      59335949,
      59335948,
      59335947,
      59335946,
      59335945,
      59335944,
      59335943,
      59335942,
      59335941,
      59335940,
      59335939,
      59335938,
      59335937,
      59335936,
      59335935,
      59335934,
      59335933,
      59335932,
      59335931,
      59335930,
      59335929,
      59335928,
      59335927,
      59335926,
      59335925,
      59335924,
      59335923,
      59335922,
      59335921,
      59335920,
      59335919,
      59335918,
      59335917,
      59335916,
      59335915,
      59335914,
      59335913,
      59335912,
      59335911,
      59335910,
      59335909,
      59335908,
      59335907,
      59335906,
      59335905,
      59335904,
      59335903,
      59335902,
      59335901,
      59335900,
      59335899,
      59335898,
      59335897,
      59335896,
      59335895,
      59335894,
      59335893,
      59335733,
      59335732,
      59335731,
      59335730,
      59335729,
      59335728,
      59335727,
      59335726,
      59335725,
      59335724,
      59335723,
      59335722,
      59335721,
      59335720,
      59335719,
      59335718,
      59335717,
      59335716,
      59335715,
      59335714,
      59335713,
      59335712,
      59335711,
      59335710,
      59335709,
      59335708,
      59335707,
      59335706,
      59335705,
      59335704,
      59335703,
      59335702,
      59335701,
      59335700,
      59335699,
      59335698,
      59335697,
      59335696,
      59335695,
      59335694,
      59335693,
      59335692,
      59335691,
      59335690,
      59335689,
      59335688,
      59335687,
      59335686,
      59335685,
      59335684,
      59335683,
      59335682,
      59335681,
      59335680,
      59335679,
      59335678,
      59335677,
      59335676,
      59335675,
      59335674,
      59335673,
      59335672,
      59335671,
      59335670,
      59335669,
      59335668,
      59335667,
      59335666,
      59335665,
      59335664,
      59335663,
      59335662,
      59335661,
      59335660,
      59335659,
      59335658,
      59335657,
      59335656,
      59335655,
      59335654,
      59335653,
      59335652,
      59335651,
      59335650,
      59335649,
      59335648,
      59335647,
      59335646,
      59335645,
      59335644,
      59335643,
      59335642,
      59335641,
      59335640,
      59335639,
      59335638,
      59335637,
      59335636,
      59335635,
      59335634,
      59335633,
      59335632,
      59335631,
      59335630,
      59335629,
      59335628,
      59335627,
      59335626,
      59335625,
      59335624,
      59335623,
      59335622,
      59335621,
      59335620,
      59335619,
      59335618,
      59335617,
      59335616,
      59335615,
      59335614,
      59335613,
      59335612,
      59335611,
      59335610,
      59335609,
      59335608,
      59335607,
      59335606,
      59335605,
      59335604,
      59335603,
      59335602,
      59335601,
      59335600,
      59335599,
      59335598,
      59335597,
      59335596,
      59335595,
      59335594,
      59335593,
      59335592,
      59335591,
      59335590,
      59335589,
      59335588,
      59335587,
      59335586,
      59335585,
      59335584,
      59335583,
      59335582,
      59335581,
      59335580,
      59335579,
      59335578,
      59335576,
      59335575,
      59335574,
      59335573,
      59335572,
      59335571,
      59335570,
      59335569,
      59335568,
      59335567,
      59335566,
      59335565,
      59335564,
      59335563,
      59335562,
      59335561,
      59335560,
      59335559,
      59335558,
      59335557,
      59335556,
      59335555,
      59335554,
      59335553,
      59335552,
      59335551,
      59335550,
      59335549,
      59335548,
      59335547,
      59335546,
      59335545,
      59335544,
      59335543,
      59335542,
      59335541,
      59335540,
      59335539,
      59335538,
      59335537,
      59335536,
      59335535,
      59335534,
      59335533,
      59335532,
      59335531,
      59335530,
      59335529,
      59335528,
      59335527,
      59335526,
      59335525,
      59335524,
      59335523,
      59335522,
      59335521,
      59335520,
      59335519,
      59335518,
      59335517,
      59335516,
      59335515,
      59335514,
      59335513,
      59335512,
      59335511,
      59335510,
      59335509,
      59335508,
      59335507,
      59335506,
      59335505,
      59335504,
      59335503,
      59335502,
      59335501,
      59335500,
      59335499,
      59335498,
      59335497,
      59335496,
      59335495,
      59335494,
      59335493,
      59335492,
      59335491,
      59335490,
      59335489,
      59335488,
      59335487,
      59335486,
      59335485,
      59335484,
      59335483,
      59335482,
      59335481,
      59335480,
      59335479,
      59335478,
      59335477,
      59335476,
      59335475,
      59335474,
      59335473,
      59335472,
      59335471,
      59335470,
      59335469,
      59335468,
      59335467,
      59335466,
      59335465,
      59335464,
      59335463,
      59335462,
      59335461,
      59335460,
      59335459,
      59335458,
      59335457,
      59335456,
      59335455,
      59335454,
      59335453,
      59335452,
      59335451,
      59335450,
      59335449,
      59335448,
      59335447,
      59335446,
      59335445,
      59335444,
      59335443,
      59335442,
      59335441,
      59335440,
      59335439,
      59335438,
      59335437,
      59335436,
      59335435,
      59335434,
      59335433,
      59335432,
      59335431,
      59335430,
      59335429,
      59335428,
      59335427,
      59335426,
      59335425,
      59335424,
      59335423,
      59335422,
      59335421,
      59335420,
      59335419,
      59335418,
      59335417,
      59335416,
      59335415,
      59335414,
      59335413,
      59335412,
      59335411,
      59335410,
      59335409,
      59335408,
      59335407,
      59335406,
      59335405,
      59335404,
      59335403,
      59335402,
      59335401,
      59335400,
      59335399,
      59335398,
      59335397,
      59335396,
      59335395,
      59335394,
      59335393,
      59335392,
      59335391,
      59335390,
      59335389,
      59335388,
      59335387,
      59335386,
      59335385,
      59335384,
      59335383,
      59335382,
      59335381,
      59335380,
      59335379,
      59335378,
      59335377,
      59335376,
      59335375,
      59335374,
      59335373,
      59335372,
      59335371,
      59335370,
      59335369,
      59335368,
      59335367,
      59335366,
      59335365,
      59335364,
      59335363,
      59335362,
      59335361,
      59335360,
      59335359,
      59335358,
      59335357,
      59335356,
      59335355,
      59335354,
      59335353,
      59335352,
      59335351,
      59335350,
      59335349,
      59335348,
      59335347,
      59335346,
      59335345,
      59335344,
      59335343,
      59335342,
      59335341,
      59335340,
      59335339,
      59335338,
      59335337,
      59335336,
      59335335,
      59335334,
      59335333,
      59335332,
      59335331,
      59335330,
      59335329,
      59335328,
      59335327,
      59335326,
      59335325,
      59335324,
      59335323,
      59335322,
      59335321,
      59335320,
      59335319,
      59335318,
      59335317,
      59335316,
      59335315,
      59335314,
      59335313,
      59335312,
      59335311,
      59335310,
      59335309,
      59335308,
      59335307,
      59335306,
      59335305,
      59335304,
      59335303,
      59335302,
      59335301,
      59335300,
      59335299,
      59335298,
      59335297,
      59335296,
      59335295,
      59335294,
      59335293,
      59335292,
      59335291,
      59335290,
      59335289,
      59335288,
      59335287,
      59335286,
      59335285,
      59335284,
      59335283,
      59335282,
      59335281,
      59335280,
      59335279,
      59335278,
      59335277,
      59335276,
      59335275,
      59335274,
      59335273,
      59335272,
      59335271,
      59335270,
      59335269,
      59335268,
      59335267,
      59335266,
      59335265,
      59335264,
      59335263,
      59335262,
      59335261,
      59335260,
      59335259,
      59335258,
      59335257,
      59335256,
      59335255,
      59335254,
      59335253,
      59335252,
      59335251,
      59335250,
      59335249,
      59335248,
      59335247,
      59335246,
      59335245,
      59335244,
      59335243,
      59335242,
      59335241,
      59335240,
      59335239,
      59335238,
      59335237,
      59335236,
      59335235,
      59335234,
      59335233,
      59335232,
      59335231,
      59335230,
      59335229,
      59335228,
      59335227,
      59335226,
      59335225,
      59335224,
      59335223,
      59335222,
      59335221,
      59335220,
      59335219,
      59335218,
      59335217,
      59335216,
      59335215,
      59335214,
      59335213,
      59335212,
      59335211,
      59335210,
      59335209,
      59335208,
      59335207,
      59335206,
      59335205,
      59335204,
      59335203,
      59335202,
      59335201,
      59335200,
      59335199,
      59335198,
      59335197,
      59335196,
      59335195,
      59335194,
      59335193,
      59335192,
      59335191,
      59335190,
      59335189,
      59335188,
      59335187,
      59335186,
      59335185,
      59335184,
      59335183,
      59335182,
      59335181,
      59335180,
      59335179,
      59335178,
      59335177,
      59335176,
      59335175,
      59335174,
      59335173,
      59335172,
      59335171,
      59335170,
      59335169,
      59335168,
      59335167,
      59335166,
      59335165,
      59335164,
      59335163,
      59335162,
      59335161,
      59335160,
      59335159,
      59335158,
      59335157,
      59335156,
      59335155,
      59335154,
      59335153,
      59335152,
      59335151,
      59335150,
      59335149,
      59335148,
      59335147,
      59335146,
      59335145,
      59335144,
      59335143,
      59335142,
      59335141,
      59335140,
      59335139,
      59335138,
      59335137,
      59335136,
      59335135,
      59335134,
      59335133,
      59335132,
      59335131,
      59335130,
      59335129,
      59335128,
      59335127,
      59335126,
      59335125,
      59335124,
      59335123,
      59335122,
      59335121,
      59335120,
      59335119,
      59335118,
      59335117,
      59335116,
      59335115,
      59335114,
      59335113,
      59335112,
      59335111,
      59335110,
      59335109,
      59335108,
      59335107,
      59335106,
      59335105,
      59335104,
      59335103,
      59335102,
      59335101,
      59335100,
      59335099,
      59335098,
      59335097,
      59335096,
      59335095,
      59335094,
      59335093,
      59335092,
      59335091,
      59335090,
      59335089,
      59335088,
      59335087,
      59335086,
      59335085,
      59335084,
      59335083,
      59335082,
      59335081,
      59335080,
      59335079,
      59335078,
      59335077,
      59335076,
      59335075,
      59335074,
      59335073,
      59335072,
      59335071,
      59335070,
      59335069,
      59335068,
      59335067,
      59335066,
      59335065,
      59335064,
      59335063,
      59335062,
      59335061,
      59335060,
      59335059,
      59335058,
      59335057,
      59335056,
      59335055,
      59335054,
      59335053,
      59335052,
      59335051,
      59335050,
      59335049,
      59335048,
      59335047,
      59335046,
      59335045,
      59335044,
      59335043,
      59335042,
      59335041,
      59335040,
      59335039,
      59335038,
      59335037,
      59335036,
      59335035,
      59335034,
      59335033,
      59335032,
      59335031,
      59335030,
      59335029,
      59335028,
      59335027,
      59335026,
      59335025,
      59335024,
      59335023,
      59335022,
      59335021,
      59335020,
      59335019,
      59335018,
      59335017,
      59335016,
      59335015,
      59335014,
      59335013,
      59335012,
      59335011,
      59335010,
      59335009,
      59335008,
      59335007,
      59335006,
      59335005,
      59335004,
      59335003,
      59335002,
      59335001,
      59335000,
      59334999,
      59334998,
      59334997,
      59334996,
      59334995,
      59334994,
      59334993,
      59334992,
      59334991,
      59334990,
      59334989,
      59334988,
      59334987,
      59334986,
      59334985,
      59334984,
      59334983,
      59334982,
      59334981,
      59334980,
      59334979,
      59334978,
      59334977,
      59334976,
      59334975,
      59334974,
      59334973,
      59334972,
      59334971,
      59334970,
      59334969,
      59334968,
      59334967,
      59334966,
      59334965,
      59334964,
      59334963,
      59334962,
      59334961,
      59334960,
      59334959,
      59334958,
      59334957,
      59334956,
      59334955,
      59334954,
      59334953,
      59334952,
      59334951,
      59334950,
      59334949,
      59334948,
      59334947,
      59334946,
      59334945,
      59334944,
      59334943,
      59334942,
      59334941,
      59334940,
      59334939,
      59334938,
      59334937,
      59334936,
      59334935,
      59334934,
      59334933,
      59334932,
      59334931,
      59334930,
      59334929,
      59334928,
      59334927,
      59334926,
      59334925,
      59334924,
      59334923,
      59334922,
      59334921,
      59334920,
      59334919,
      59334918,
      59334917,
      59334916,
      59334915,
      59334914,
      59334913,
      59334912,
      59334911,
      59334910,
      59334909,
      59334908,
      59334907,
      59334906,
      59334905,
      59334904,
      59334903,
      59334902,
      59334901,
      59334900,
      59334899,
      59334898,
      59334897,
      59334896,
      59334895,
      59334894,
      59334893,
      59334892,
      59334891,
      59334890,
      59334889,
      59334888,
      59334887,
      59334886,
      59334885,
      59334884,
      59334883,
      59334882,
      59334881,
      59334880,
      59334879,
      59334878,
      59334877,
      59334876,
      59334875,
      59334874,
      59334873,
      59334872,
      59334871,
      59334870,
      59334869,
      59334868,
      59334867,
      59334866,
      59334865,
      59334864,
      59334863,
      59334862,
      59334861,
      59334860,
      59334859,
      59334858,
      59334857,
      59334856,
      59334855,
      59334854,
      59334853,
      59334852,
      59334851,
      59334850,
      59334849,
      59334848,
      59334847,
      59334846,
      59334845,
      59334844,
      59334843,
      59334842,
      59334841,
      59334840,
      59334839,
      59334838,
      59334837,
      59334836,
      59334835,
      59334834,
      59334833,
      59334832,
      59334831,
      59334830,
      59334829,
      59334828,
      59334827,
      59334826,
      59334825,
      59334824,
      59334823,
      59334822,
      59334821,
      59334820,
      59334819,
      59334818,
      59334817,
      59334816,
      59334815,
      59334814,
      59334813,
      59334812,
      59334811,
      59334810,
      59334809,
      59334808,
      59334807,
      59334806,
      59334805,
      59334804,
      59334803,
      59334802,
      59334801,
      59334800,
      59334799,
      59334798,
      59334797,
      59334796,
      59334795,
      59334794,
      59334793,
      59334792,
      59334791,
      59334790,
      59334789,
      59334788,
      59334787,
      59334786,
      59334785,
      59334784,
      59334783,
      59334782,
      59334781,
      59334780,
      59334779,
      59334778,
      59334777,
      59334776,
      59334775,
      59334774,
      59334773,
      59334772,
      59334771,
      59334770,
      59334769,
      59334768,
      59334767,
      59334766,
      59334765,
      59334764,
      59334763,
      59334762,
      59334761,
      59334760,
      59334759,
      59334758,
      59334757,
      59334756,
      59334755,
      59334754,
      59334753,
      59334752,
      59334751,
      59334750,
      59334749,
      59334748,
      59334747,
      59334746,
      59334745,
      59334744,
      59334743,
      59334742,
      59334741,
      59334740,
      59334739,
      59334738,
      59334737,
      59334736,
      59334735,
      59334734,
      59334733,
      59334732,
      59334731,
      59334730,
      59334729,
      59334728,
      59334727,
      59334726,
      59334725,
      59334724,
      59334723,
      59334722,
      59334721,
      59334720,
      59334719,
      59334718,
      59334717,
      59334716,
      59334715,
      59334714,
      59334713,
      59334712,
      59334711,
      59334710,
      59334709,
      59334708,
      59334707,
      59334706,
      59334705,
      59334704,
      59334703,
      59334702,
      59334701,
      59334700,
      59334699,
      59334698,
      59334697,
      59334696,
      59334695,
      59334694,
      59334693,
      59334692,
      59334691,
      59334690,
      59334689,
      59334688,
      59334687,
      59334686,
      59334685,
      59334684,
      59334683,
      59334682,
      59334681,
      59334680,
      59334679,
      59334678,
      59334677,
      59334676,
      59334675,
      59334674,
      59334673,
      59334672,
      59334671,
      59334670,
      59334669,
      59334668,
      59334667,
      59334666,
      59334665,
      59334664,
      59334663,
      59334662,
      59334661,
      59334660,
      59334659,
      59334658,
      59334657,
      59334656,
      59334655,
      59334654,
      59334653,
      59334652,
      59334651,
      59334650,
      59334649,
      59334648,
      59334647,
      59334646,
      59334645,
      59334644,
      59334643,
      59334642,
      59334641,
      59334640,
      59334639,
      59334638,
      59334637,
      59334636,
      59334635,
      59334634,
      59334633,
      59334632,
      59334631,
      59334630,
      59334629,
      59334628,
      59334627,
      59334626,
      59334625,
      59334624,
      59334623,
      59334622,
      59334621,
      59334620,
      59334619,
      59334618,
      59334617,
      59334616,
      59334615,
      59334614,
      59334613,
      59334612,
      59334611,
      59334610,
      59334609,
      59334608,
      59334607,
      59334606,
      59334605,
      59334604,
      59334603,
      59334602,
      59334601,
      59334600,
      59334599,
      59334598,
      59334597,
      59334596,
      59334595,
      59334594,
      59334593,
      59334592,
      59334591,
      59334590,
      59334589,
      59334588,
      59334587,
      59334586,
      59334585,
      59334584,
      59334583,
      59334582,
      59334581,
      59334580,
      59334579,
      59334578,
      59334577,
      59334576,
      59334575,
      59334574,
      59334573,
      59334572,
      59334571,
      59334570,
      59334569,
      59334568,
      59334567,
      59334566,
      59334565,
      59334564,
      59334563,
      59334562,
      59334561,
      59334560,
      59334559,
      59334558,
      59334557,
      59334556,
      59334555,
      59334554,
      59334553,
      59334552,
      59334551,
      59334550,
      59334549,
      59334548,
      59334547,
      59334546,
      59334545,
      59334544,
      59334543,
      59334542,
      59334541,
      59334540,
      59334539,
      59334538,
      59334537,
      59334536,
      59334535,
      59334534,
      59334533,
      59334532,
      59334531,
      59334530,
      59334529,
      59334528,
      59334527,
      59334526,
      59334525,
      59334524,
      59334523,
      59334522,
      59334521,
      59334520,
      59334519,
      59334518,
      59334517,
      59334516,
      59334515,
      59334514,
      59334513,
      59334512,
      59334478,
      59334477,
      59334476,
      59334475,
      59334474,
      59334473,
      59334472,
      59334471,
      59334470,
      59334469,
      59334468,
      59334467,
      59334466,
      59334465,
      59334464,
      59334463,
      59334462,
      59334461,
      59334460,
      59334459,
      59334458,
      59334457,
      59334456,
      59334455,
      59334454,
      59334453,
      59334452,
      59334451,
      59334450,
      59334449,
      59334448,
      59334447,
      59334446,
      59334445,
      59334444,
      59334443,
      59334442,
      59334441,
      59334440,
      59334439,
      59334438,
      59334437,
      59334436,
      59334435,
      59334434,
      59334433,
      59334432,
      59334431,
      59334430,
      59334429,
      59334428,
      59334427,
      59334426,
      59334425,
      59334424,
      59334423,
      59334422,
      59334421,
      59334420,
      59334419,
      59334418,
      59334417,
      59334416,
      59334415,
      59334414,
      59334413,
      59334412,
      59334411,
      59334410,
      59334409,
      59334408,
      59334407,
      59334406,
      59334405,
      59334404,
      59334403,
      59334402,
      59334401,
      59334400,
      59334399,
      59334398,
      59334397,
      59334396,
      59334395,
      59334394,
      59334393,
      59334392,
      59334391,
      59334390,
      59334389,
      59334388,
      59334387,
      59334386,
      59334385,
      59334384,
      59334383,
      59334382,
      59334381,
      59334380,
      59334379,
      59334378,
      59334377,
      59334376,
      59334375,
      59334374,
      59334373,
      59334372,
      59334371,
      59334370,
      59334369,
      59334368,
      59334367,
      59334366,
      59334365,
      59334364,
      59334363,
      59334362,
      59334361,
      59334360,
      59334359,
      59334358,
      59334357,
      59334356,
      59334355,
      59334354,
      59334353,
      59334352,
      59334351,
      59334350,
      59334349,
      59334348,
      59334347,
      59334346,
      59334345,
      59334344,
      59334343,
      59334342,
      59334341,
      59334340,
      59334339,
      59334338,
      59334337,
      59334336,
      59334335,
      59334334,
      59334333,
      59334332,
      59334331,
      59334330,
      59334329,
      59334328,
      59334327,
      59334326,
      59334325,
      59334324,
      59334323,
      59334320,
      59334319,
      59334318,
      59334317,
      59334316,
      59334315,
      59334314,
      59334313,
      59334312,
      59334311,
      59334310,
      59334309,
      59334308,
      59334307,
      59334306,
      59334305,
      59334304,
      59334303,
      59334302,
      59334301,
      59334300,
      59334299,
      59334298,
      59334297,
      59334296,
      59334295,
      59334294,
      59334293,
      59334292,
      59334291,
      59334290,
      59334289,
      59334288,
      59334287,
      59334286,
      59334285,
      59334284,
      59334283,
      59334282,
      59334281,
      59334280,
      59334279,
      59334278,
      59334277,
      59334276,
      59334275,
      59334274,
      59334273,
      59334272,
      59334271,
      59334270,
      59334269,
      59334268,
      59334267,
      59334266,
      59334265,
      59334264,
      59334263,
      59334262,
      59334261,
      59334260,
      59334259,
      59334258,
      59334257,
      59334256,
      59334255,
      59334254,
      59334253,
      59334252,
      59334251,
      59334250,
      59334249,
      59334248,
      59334247,
      59334246,
      59334245,
      59334244,
      59334243,
      59334242,
      59334241,
      59334240,
      59334239,
      59334238,
      59334236,
      59334235,
      59334234,
      59334233,
      59334232,
      59334231,
      59334230,
      59334229,
      59334228,
      59334227,
      59334226,
      59334225,
      59334224,
      59334223,
      59334222,
      59334221,
      59334220,
      59334219,
      59334218,
      59334217,
      59334216,
      59334215,
      59334214,
      59334213,
      59334212,
      59334211,
      59334210,
      59334209,
      59334208,
      59334207,
      59334206,
      59334205,
      59334204,
      59334203,
      59334202,
      59334201,
      59334200,
      59334199,
      59334198,
      59334197,
      59334196,
      59334195,
      59334194,
      59334193,
      59333260,
      59333259,
      59333258,
      59333257,
      59333256,
      59333255,
      59333254,
      59333253,
      59333252,
      59333251,
      59333250,
      59333249,
      59333248,
      59333247,
      59333246,
      59333245,
      59333244,
      59333243,
      59333242,
      59333241,
      59333240,
      59333239,
      59333238,
      59333237,
      59333236,
      59333235,
      59333234,
      59333233,
      59333232,
      59333231,
      59333230,
      59333229,
      59333228,
      59333227,
      59333226,
      59333225,
      59333224,
      59333223,
      59333222,
      59333221,
      59333220,
      59333219,
      59333218,
      59333217,
      59333216,
      59333215,
      59333214,
      59333213,
      59333212,
      59333211,
      59333210,
      59333209,
      59333208,
      59333207,
      59333206,
      59333205,
      59333204,
      59333203,
      59333202,
      59333201,
      59333200,
      59333199,
      59333198,
      59333197,
      59333196,
      59333195,
      59333194,
      59333193,
      59333192,
      59333191,
      59333190,
      59333189,
      59333188,
      59333187,
      59333186,
      59333185,
      59333184,
      59333183,
      59333182,
      59333181,
      59333180,
      59333179,
      59333178,
      59333177,
      59333176,
      59333175,
      59333174,
      59333173,
      59333172,
      59333171,
      59333170,
      59333169,
      59333168,
      59333167,
      59333166,
      59333165,
      59333164,
      59333163,
      59333162,
      59333161,
      59333160,
      59333159,
      59333158,
      59333157,
      59333156,
      59333155,
      59333154,
      59333153,
      59333152,
      59333151,
      59333150,
      59333149,
      59333148,
      59333147,
      59333146,
      59333145,
      59333144,
      59333143,
      59333142,
      59333141,
      59333140,
      59333139,
      59333138,
      59333137,
      59333136,
      59333135,
      59333134,
      59333133,
      59333132,
      59333131,
      59333130,
      59333129,
      59333128,
      59333127,
      59333126,
      59333125,
      59333124,
      59333123,
      59333122,
      59333121,
      59333120,
      59333119,
      59333118,
      59333117,
      59333116,
      59333115,
      59333114,
      59333113,
      59333112,
      59333111,
      59333110,
      59333109,
      59333108,
      59333107,
      59333106,
      59333105,
      59333104,
      59333103,
      59333102,
      59333101,
      59333100,
      59333099,
      59333098,
      59333097,
      59333096,
      59333095,
      59333094,
      59333093,
      59333092,
      59333091,
      59333090,
      59333089,
      59333088,
      59333087,
      59333086,
      59333085,
      59333084,
      59333083,
      59333082,
      59333081,
      59333080,
      59333079,
      59333078,
      59333077,
      59333076,
      59333075,
      59333074,
      59333073,
      59333072,
      59333071,
      59333070,
      59333069,
      59333068,
      59333067,
      59333066,
      59333065,
      59333064,
      59333063,
      59333062,
      59333061,
      59333060,
      59333059,
      59333058,
      59333057,
      59333056,
      59333055,
      59333054,
      59333053,
      59333052,
      59333051,
      59333050,
      59333049,
      59333048,
      59333047,
      59333046,
      59333045,
      59333044,
      59333043,
      59333042,
      59333041,
      59333040,
      59333039,
      59333038,
      59333037,
      59333036,
      59333035,
      59333034,
      59333033,
      59333032,
      59333031,
      59333030,
      59333029,
      59333028,
      59333027,
      59333026,
      59333025,
      59333024,
      59333023,
      59333022,
      59333021,
      59333020,
      59333019,
      59333018,
      59333017,
      59333016,
      59333015,
      59333014,
      59333013,
      59333012,
      59333011,
      59333010,
      59333009,
      59333008,
      59333007,
      59333006,
      59333005,
      59333004,
      59333003,
      59333002,
      59333001,
      59333000,
      59332999,
      59332998,
      59332997,
      59332996,
      59332995,
      59332994,
      59332993,
      59332992,
      59332991,
      59332990,
      59332989,
      59332988,
      59332987,
      59332986,
      59332985,
      59332984,
      59332983,
      59332982,
      59332981,
      59332980,
      59332979,
      59332978,
      59332977,
      59332976,
      59332975,
      59332974,
      59332973,
      59332972,
      59332971,
      59332970,
      59332969,
      59332968,
      59332967,
      59332966,
      59332965,
      59332964,
      59332963,
      59332962,
      59332961,
      59332960,
      59332959,
      59332958,
      59332957,
      59332956,
      59332955,
      59332954,
      59332953,
      59332952,
      59332951,
      59332950,
      59332949,
      59332948,
      59332947,
      59332946,
      59332945,
      59332944,
      59332943,
      59332942,
      59332941,
      59332940,
      59332939,
      59332938,
      59332937,
      59332936,
      59332935,
      59332934,
      59332933,
      59332932,
      59332931,
      59332930,
      59332929,
      59332928,
      59332927,
      59332926,
      59332925,
      59332924,
      59332923,
      59332922,
      59332921,
      59332920,
      59332919,
      59332918,
      59332917,
      59332916,
      59332915,
      59332914,
      59332913,
      59332912,
      59332911,
      59332910,
      59332909,
      59332908,
      59332907,
      59332906,
      59332905,
      59332904,
      59332903,
      59332902,
      59332901,
      59332900,
      59332899,
      59332898,
      59332897,
      59332896,
      59332895,
      59332894,
      59332893,
      59332892,
      59332891,
      59332890,
      59332889,
      59332888,
      59332887,
      59332886,
      59332885,
      59332884,
      59332883,
      59332882,
      59332881,
      59332880,
      59332879,
      59332878,
      59332877,
      59332876,
      59332875,
      59332874,
      59332873,
      59332872,
      59332871,
      59332870,
      59332869,
      59332868,
      59332867,
      59332866,
      59332865,
      59332864,
      59332863,
      59332862,
      59332861,
      59332860,
      59332859,
      59332858,
      59332857,
      59332856,
      59332855,
      59332854,
      59332853,
      59332852,
      59332851,
      59332850,
      59332849,
      59332848,
      59332847,
      59332846,
      59332845,
      59332844,
      59332843,
      59332842,
      59332841,
      59332840,
      59332839,
      59332838,
      59332837,
      59332836,
      59332835,
      59332834,
      59332833,
      59332832,
      59332831,
      59332830,
      59332829,
      59332828,
      59332827,
      59332826,
      59332825,
      59332824,
      59332823,
      59332822,
      59332821,
      59332820,
      59332819,
      59332818,
      59332817,
      59332816,
      59332815,
      59332814,
      59332813,
      59332812,
      59332811,
      59332810,
      59332809,
      59332808,
      59332807,
      59332806,
      59332805,
      59332804,
      59332803,
      59332802,
      59332801,
      59332800,
      59332799,
      59332798,
      59332797,
      59332796,
      59332795,
      59332794,
      59332793,
      59332792,
      59332791,
      59332790,
      59332789,
      59332788,
      59332787,
      59332786,
      59332785,
      59332784,
      59332783,
      59332782,
      59332781,
      59332780,
      59332779,
      59332778,
      59332777,
      59332776,
      59332775,
      59332774,
      59332773,
      59332772,
      59332771,
      59332770,
      59332769,
      59332768,
      59332767,
      59332766,
      59332765,
      59332764,
      59332763,
      59332762,
      59332761,
      59332760,
      59332759,
      59332758,
      59332757,
      59332756,
      59332755,
      59332754,
      59332753,
      59332752,
      59332751,
      59332750,
      59332749,
      59332748,
      59332747,
      59332746,
      59332745,
      59332744,
      59332743,
      59332742,
      59332741,
      59332740,
      59332739,
      59332738,
      59332737,
      59332736,
      59332735,
      59332734,
      59332733,
      59332732,
      59332731,
      59332730,
      59332729,
      59332728,
      59332727,
      59332726,
      59332725,
      59332724,
      59332723,
      59332722,
      59332721,
      59332720,
      59332719,
      59332718,
      59332717,
      59332716,
      59332715,
      59332714,
      59332713,
      59332712,
      59332711,
      59332710,
      59332709,
      59332708,
      59332707,
      59332706,
      59332705,
      59332704,
      59332703,
      59332702,
      59332701,
      59332700,
      59332699,
      59332698,
      59332697,
      59332696,
      59332695,
      59332694,
      59332693,
      59332692,
      59332691,
      59332690,
      59332689,
      59332688,
      59332687,
      59332686,
      59332685,
      59332684,
      59332683,
      59332682,
      59332681,
      59332680,
      59332679,
      59332678,
      59332677,
      59332676,
      59332675,
      59332674,
      59332673,
      59332672,
      59332671,
      59332670,
      59332669,
      59332668,
      59332667,
      59332666,
      59332665,
      59332664,
      59332663,
      59332662,
      59332661,
      59332660,
      59332659,
      59332658,
      59332657,
      59332656,
      59332655,
      59332654,
      59332653,
      59332652,
      59332651,
      59332650,
      59332649,
      59332648,
      59332647,
      59332646,
      59332645,
      59332644,
      59332643,
      59332642,
      59332641,
      59332640,
      59332639,
      59332638,
      59332637,
      59332636,
      59332635,
      59332634,
      59332633,
      59332632,
      59332631,
      59332630,
      59332629,
      59332628,
      59332627,
      59332626,
      59332625,
      59332624,
      59332623,
      59332622,
      59332621,
      59332620,
      59332619,
      59332618,
      59332617,
      59332616,
      59332615,
      59332614,
      59332613,
      59332612,
      59332611,
      59332610,
      59332609,
      59332608,
      59332607,
      59332606,
      59332605,
      59332604,
      59332603,
      59332602,
      59332601,
      59332600,
      59332599,
      59332598,
      59332597,
      59332596,
      59332595,
      59332594,
      59332593,
      59332592,
      59332591,
      59332590,
      59332589,
      59332588,
      59332587,
      59332586,
      59332585,
      59332584,
      59332583,
      59332582,
      59332581,
      59332580,
      59332579,
      59332578,
      59332577,
      59332576,
      59332575,
      59332574,
      59332573,
      59332572,
      59332571,
      59332570,
      59332569,
      59332568,
      59332567,
      59332566,
      59332565,
      59332564,
      59332563,
      59332562,
      59332561,
      59332560,
      59332559,
      59332558,
      59332557,
      59332556,
      59332555,
      59332554,
      59332553,
      59332552,
      59332551,
      59332550,
      59332549,
      59332548,
      59332547,
      59332546,
      59332545,
      59332544,
      59332543,
      59332542,
      59332541,
      59332540,
      59332539,
      59332538,
      59332537,
      59332536,
      59332535,
      59332534,
      59332533,
      59332532,
      59332531,
      59332530,
      59332529,
      59332528,
      59332527,
      59332526,
      59332525,
      59332524,
      59332523,
      59332522,
      59332521,
      59332520,
      59332519,
      59332518,
      59332517,
      59332516,
      59332515,
      59332514,
      59332513,
      59332512,
      59332511,
      59332510,
      59332509,
      59332508,
      59332507,
      59332506,
      59332505,
      59332504,
      59332503,
      59332502,
      59332501,
      59332500,
      59332499,
      59332498,
      59332497,
      59332496,
      59332495,
      59332494,
      59332493,
      59332492,
      59332491,
      59332490,
      59332489,
      59332488,
      59332487,
      59332486,
      59332485,
      59332484,
      59332483,
      59332482,
      59332481,
      59332480,
      59332479,
      59332478,
      59332477,
      59332476,
      59332475,
      59332474,
      59332473,
      59332472,
      59332471,
      59332470,
      59332469,
      59332468,
      59332467,
      59332466,
      59332465,
      59332464,
      59332463,
      59332462,
      59332461,
      59332460,
      59332459,
      59332458,
      59332457,
      59332456,
      59332455,
      59332454,
      59332453,
      59332452,
      59332451,
      59332450,
      59332449,
      59332448,
      59332447,
      59332446,
      59332445,
      59332444,
      59332443,
      59332442,
      59332441,
      59332440,
      59332439,
      59332438,
      59332437,
      59332436,
      59332435,
      59332434,
      59332433,
      59332432,
      59332431,
      59332430,
      59332429,
      59332428,
      59332427,
      59332426,
      59332425,
      59332424,
      59332423,
      59332422,
      59332421,
      59332420,
      59332419,
      59332418,
      59332417,
      59332416,
      59332415,
      59332414,
      59332413,
      59332412,
      59332411,
      59332410,
      59332409,
      59332408,
      59332407,
      59332406,
      59332405,
      59332404,
      59332403,
      59332402,
      59332401,
      59332400,
      59332399,
      59332398,
      59332397,
      59332396,
      59332395,
      59332394,
      59332393,
      59332392,
      59332391,
      59332390,
      59332389,
      59332388,
      59332387,
      59332386,
      59332385,
      59332384,
      59332383,
      59332382,
      59332381,
      59332380,
      59332379,
      59332378,
      59332377,
      59332376,
      59332375,
      59332374,
      59332373,
      59332372,
      59332371,
      59332370,
      59332369,
      59332368,
      59332367,
      59332366,
      59332365,
      59332364,
      59332363,
      59332362,
      59332361,
      59332360,
      59332359,
      59332358,
      59332357,
      59332356,
      59332355,
      59332354,
      59332353,
      59332352,
      59332351,
      59332350,
      59332349,
      59332348,
      59332347,
      59332346,
      59332345,
      59332344,
      59332343,
      59332342,
      59332341,
      59332340,
      59332339,
      59332338,
      59332337,
      59332336,
      59332335,
      59332334,
      59332333,
      59332332,
      59332331,
      59332330,
      59332329,
      59332328,
      59332327,
      59332326,
      59332325,
      59332324,
      59332323,
      59332322,
      59332321,
      59332320,
      59332319,
      59332318,
      59332317,
      59332316,
      59332315,
      59332314,
      59332313,
      59332312,
      59332311,
      59332310,
      59332309,
      59332308,
      59332307,
      59332306,
      59332305,
      59332304,
      59332303,
      59332302,
      59332301,
      59332300,
      59332299,
      59332298,
      59332297,
      59332296,
      59332295,
      59332294,
      59332293,
      59332292,
      59332291,
      59332290,
      59332289,
      59332288,
      59332287,
      59332286,
      59332285,
      59332284,
      59332283,
      59332282,
      59332281,
      59332280,
      59332279,
      59332278,
      59332277,
      59332276,
      59332275,
      59332274,
      59332273,
      59332272,
      59332271,
      59332270,
      59332269,
      59332268,
      59332267,
      59332266,
      59332265,
      59332264,
      59332263,
      59332262,
      59332261,
      59332260,
      59332259,
      59332258,
      59332257,
      59332256,
      59332255,
      59332254,
      59332253,
      59332252,
      59332251,
      59332250,
      59332249,
      59332248,
      59332247,
      59332246,
      59332245,
      59332244,
      59332243,
      59332242,
      59332241,
      59332240,
      59332239,
      59332238,
      59332237,
      59332236,
      59332235,
      59332234,
      59332233,
      59332232,
      59332231,
      59332230,
      59332229,
      59332228,
      59332227,
      59332226,
      59332225,
      59332224,
      59332223,
      59332222,
      59332221,
      59332220,
      59332219,
      59332218,
      59332217,
      59332216,
      59332215,
      59332214,
      59332213,
      59332212,
      59332211,
      59332210,
      59332209,
      59332208,
      59332207,
      59332206,
      59332205,
      59332204,
      59332203,
      59332202,
      59332201,
      59332200,
      59332199,
      59332198,
      59332197,
    ])->update([
      'deleted' => 1
    ]);
    // foreach ($rawDatas as $key => $raw) {
    //   $rawData = RawData::find($raw->id);
    //   if ($rawData) {
    //       $auditData = Audit::where('raw_data_id', $rawData->id)->first();        
    //       if ($auditData) {
    //           $auditResults = AuditResult::where('audit_id', $auditData->id)->where('sub_parameter_id', 4361)->where('selected_option', 3)->first();
    //           if ($auditResults) {
    //               $resultData = AuditResult::find($auditResults->id);
    //               $resultData->selected_option = 4;
    //               $resultData->is_critical = 0;
    //               $resultData->save();
    //           }
    //           $audRes = AuditResult::where('audit_id', $auditData->id)->where('is_critical', 1)->get();
    //           if ($audRes->isEmpty()) {
    //               $auditData->with_fatal_score_per = $auditData->overall_score;
    //               $auditData->is_critical = 0;
    //               $auditData->save();
    //           }
    //       } else {
    //           \Log::warning("No audit data found for raw_data_id: ");
    //       }
    //   }
    // }  
    // dd("done");
  }
}
