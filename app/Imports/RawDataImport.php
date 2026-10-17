<?php

namespace App\Imports;

ini_set('memory_limit', '-1');
set_time_limit(0);

use App\FailedRawDumpRow;
use App\RawData;
use App\Region;
use App\User;
use App\PartnerLocation;
use Auth;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;


class RawDataImport implements ToModel, WithBatchInserts, WithChunkReading
{
    public $data;
    public $all_location;
    public $all_qa;
    public $all_agents;
    public $failed_rows;
    public $locations_id;


    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(array $data)
    {
        $this->data = $data;

        // get all company location
        // $this->all_location = Region::where('company_id', $this->data['company_id'])->pluck('name', 'id')->toArray();


        $this->locations_id = PartnerLocation::where('partner_id', $this->data['partner_id'])->pluck('location_id')->toArray();
        $this->all_location = Region::where('company_id', $this->data['company_id'])->whereIn('id', $this->locations_id)->pluck('name', 'id')->toArray();


        $this->all_qa = User::where('company_id', Auth::user()->company_id)->whereHas('roles', function ($query) {
            $query->where('name', '=', 'qa');
        })->pluck('email', 'id')->toArray();

        $this->all_agents = User::where('company_id', Auth::user()->company_id)->whereHas('roles', function ($query) {
            $query->where('name', '=', 'agent');
        })->pluck('email', 'id')->toArray();
    }
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {


        if ($this->data['process_id'] == 138) {

            if (isset($row[0]) && isset($row[17]) && $row[0] != 'CaseNumber') {
                try {
                    // Convert open date
                    $opendate = '-';
                    $closedate = '-';

                    if (isset($row[9])) {
                        $opendateObject = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row[9]);
                        $opendate = $opendateObject ? $opendateObject->format('Y-m-d H:i:s') : '-';
                    }

                    if (isset($row[11])) {
                        $closedateObject = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row[11]);
                        $closedate = $closedateObject ? $closedateObject->format('Y-m-d H:i:s') : '-';
                    }
                } catch (\Throwable $th) {
                    $opendate = '-';
                    $closedate = '-';
                }

                $new_rc = new RawData;
                $new_rc->dump_date = $this->data['dump_date'];
                $new_rc->batch_id = $this->data['batch_id'];
                $new_rc->company_id = $this->data['company_id'];
                $new_rc->client_id = $this->data['client_id'];
                $new_rc->partner_id = $this->data['partner_id'];
                $new_rc->process_id = $this->data['process_id'];
                $new_rc->partner_location_id = 44;

                // $new_rc->qa_id =  trim($row[18]);  // Ensure index exists
                $new_rc->qa_id = array_search(trim($row[18]), $this->all_qa);
                $new_rc->qtl_id = Auth::user()->id;
                $new_rc->case_number = trim($row[0]);
                $new_rc->case_id = isset($row[1]) ? trim($row[1]) : null;
                $new_rc->case_origin = isset($row[2]) ? trim($row[2]) : null;
                $new_rc->audit_type = isset($row[3]) ? trim($row[3]) : null;
                $new_rc->category = isset($row[4]) ? trim($row[4]) : null;
                $new_rc->current_assigned_queue = isset($row[5]) ? trim($row[5]) : null;
                $new_rc->sub_type = isset($row[6]) ? trim($row[6]) : null;
                $new_rc->case_reason = isset($row[7]) ? trim($row[7]) : null;
                $new_rc->solution = isset($row[8]) ? trim($row[8]) : null;
                $new_rc->opened_date = $opendate;
                $new_rc->relationship_number = isset($row[10]) ? trim($row[10]) : null;
                $new_rc->close_date = $closedate;
                $new_rc->partner_emp_id = isset($row[12]) ? trim($row[12]) : null;
                $new_rc->partner_agent_name = isset($row[13]) ? trim($row[13]) : null;
                $new_rc->partner_agent_tenure = isset($row[14]) ? trim($row[14]) : null;
                $new_rc->fpr = isset($row[15]) ? trim($row[15]) : null;
                $new_rc->sr_mgr = isset($row[16]) ? trim($row[16]) : null;
                $new_rc->call_id = trim($row[17]);

                // Save the new record

                $new_rc->save();
            }
        } elseif ($this->data['process_id'] == 217 || $this->data['process_id'] == 218) {

            if (isset($row[0]) && isset($row[16]) && $row[2] != 'Case ID') {
                $opendate = '-';
                try {
                    if (!empty($row[4])) {
                        if (is_numeric($row[4])) {
                            // Excel numeric date
                            $dateObj = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row[4]);
                        } else {
                            // String date (like 20-04-2026)
                            $dateObj = \DateTime::createFromFormat('d-m-Y', trim($row[4]));
                        }

                        if ($dateObj) {
                            $opendate = $dateObj->format('Y-m-d H:i:s');
                        }
                    }
                } catch (\Throwable $th) {
                    $opendate = '-';
                }

                $closedate = '-';
                try {
                    if (!empty($row[5])) {
                        if (is_numeric($row[5])) {
                            $dateObj = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row[5]);
                        } else {
                            $dateObj = \DateTime::createFromFormat('d-m-Y', trim($row[5]));
                        }

                        if ($dateObj) {
                            $closedate = $dateObj->format('Y-m-d H:i:s');
                        }
                    }
                } catch (\Throwable $th) {
                    $closedate = '-';
                }
                // dd($row[4], $row[5],  $opendate,  $closedate);

                $new_rc = new RawData;
                $new_rc->dump_date = $this->data['dump_date'];
                $new_rc->batch_id = $this->data['batch_id'];
                $new_rc->company_id = $this->data['company_id'];
                $new_rc->client_id = $this->data['client_id'];
                $new_rc->partner_id = $this->data['partner_id'];
                $new_rc->process_id = $this->data['process_id'];
                // $new_rc->partner_location_id = 44;
                $new_rc->partner_location_id = array_search(trim($row[16]), $this->all_location);


                // $new_rc->qa_id =  trim($row[18]);  // Ensure index exists
                $new_rc->qa_id = array_search(trim($row[17]), $this->all_qa);
                $new_rc->qtl_id = Auth::user()->id;
                $new_rc->month = isset($row[0]) ? trim($row[0]) : null;
                $new_rc->case_number = trim($row[1]);
                $new_rc->call_id = trim($row[1]);
                $new_rc->case_id = trim($row[2]);
                $new_rc->reference_no = trim($row[3]); // LAN NO
                $new_rc->opened_date = $opendate;
                $new_rc->close_date = $closedate;
                $new_rc->case_origin = isset($row[6]) ? trim($row[6]) : null;
                $new_rc->call_type = isset($row[7]) ? trim($row[7]) : null;
                $new_rc->call_sub_type = isset($row[8]) ? trim($row[8]) : null;
                $new_rc->case_reason = isset($row[9]) ? trim($row[9]) : null;
                // $new_rc->solution = isset($row[10]) ? trim($row[10]) : null;
                $new_rc->disposition = isset($row[10]) ? trim($row[10]) : null;
                $new_rc->user_name = isset($row[11]) ? trim($row[11]) : null;
                $new_rc->solution_based_category_c = isset($row[12]) ? trim($row[12]) : null;
                $new_rc->current_assigned_queue = isset($row[13]) ? trim($row[13]) : null;
                $new_rc->partner_name = isset($row[14]) ? trim($row[14]) : null;
                $new_rc->case_status = isset($row[15]) ? trim($row[15]) : null;
                $new_rc->location = isset($row[16]) ? trim($row[16]) : null;




                // Save the new record

                $new_rc->save();
            }
        } elseif ($this->data['process_id'] == 171) {
            if (isset($row[0]) && isset($row[13]) && $row[0] != 'CaseNumber') {
                try {
                    // Convert open date
                    $opendate = '-';
                    if (isset($row[4])) {
                        $opendateObject = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row[4]);
                        $opendate = $opendateObject ? $opendateObject->format('Y-m-d H:i:s') : '-';
                    }
                } catch (\Throwable $th) {
                    $opendate = '-';
                }

                try {
                    // Convert open date   $opendate = '-';
                    $closedate = '-';
                    $current_assigned_queue_datetime = '-';
                    if (isset($row[6])) {
                        $closedateObject = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row[6]);
                        $closedate = $closedateObject ? $closedateObject->format('Y-m-d H:i:s') : '-';
                    }
                } catch (\Throwable $th) {
                    $closedate = '-';
                }

                try {
                    // Convert open date
                    $current_assigned_queue_datetime = '-';
                    if (isset($row[5])) {
                        $current_assigned_queue_datetime_obj = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row[5]);
                        $current_assigned_queue_datetime = $current_assigned_queue_datetime_obj ? $current_assigned_queue_datetime_obj->format('Y-m-d H:i:s') : '-';
                    }
                } catch (\Throwable $th) {
                    $current_assigned_queue_datetime = '-';
                }



                $new_rc = new RawData;
                $new_rc->dump_date = $this->data['dump_date'];
                $new_rc->batch_id = $this->data['batch_id'];
                $new_rc->company_id = $this->data['company_id'];
                $new_rc->client_id = $this->data['client_id'];
                $new_rc->partner_id = $this->data['partner_id'];
                $new_rc->process_id = $this->data['process_id'];
                $new_rc->partner_location_id = 44;

                // $new_rc->qa_id =  trim($row[18]);  // Ensure index exists
                $new_rc->qa_id = array_search(trim($row[13]), $this->all_qa);
                $new_rc->qtl_id = Auth::user()->id;
                $new_rc->case_number = trim($row[0]);
                $new_rc->call_id = trim($row[0]);
                $new_rc->case_origin = isset($row[1]) ? trim($row[1]) : null;
                $new_rc->current_assigned_queue = isset($row[2]) ? trim($row[2]) : null;
                $new_rc->case_status = isset($row[3]) ? trim($row[3]) : null;
                $new_rc->opened_date = $opendate;
                $new_rc->case_create_date = $current_assigned_queue_datetime;
                $new_rc->close_date = $closedate;
                $new_rc->queue_name_c = isset($row[7]) ? trim($row[7]) : null;
                $new_rc->call_type = isset($row[8]) ? trim($row[8]) : null;
                $new_rc->relationship_number = isset($row[9]) ? trim($row[9]) : null;
                $new_rc->case_reason = isset($row[10]) ? trim($row[10]) : null;
                $new_rc->solution = isset($row[11]) ? trim($row[11]) : null;
                $new_rc->location = isset($row[12]) ? trim($row[12]) : null;
                $new_rc->lob = isset($row[14]) ? trim($row[14]) : null;
                $new_rc->language = isset($row[15]) ? trim($row[15]) : null;
                $new_rc->agent_name = isset($row[16]) ? trim($row[16]) : null;
                $new_rc->agent_id = isset($row[17]) ? trim($row[17]) : null;
                $new_rc->doj = trim($row[18]);
                $new_rc->call_duration = trim($row[19]);


                // Save the new record

                $new_rc->save();
            }
        } elseif ($this->data['process_id'] == 145 ||  $this->data['process_id'] == 156) {
            if (isset($row[0]) && isset($row[6]) && $row[0] != 'EVENT__C') {

                try {
                    // Convert open date
                    $CaseCreateDate = '-';
                    $DisbursmentDate = '-';
                    $CloseDate = '-';

                    if (isset($row[2])) {
                        $opendateObject = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row[2]);
                        $CaseCreateDate = $opendateObject ? $opendateObject->format('Y-m-d H:i:s') : '-';
                    }

                    if (isset($row[3])) {
                        $DISdateObject = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row[3]);
                        $DisbursmentDate = $DISdateObject ? $DISdateObject->format('Y-m-d H:i:s') : '-';
                    }

                    if (isset($row[5])) {
                        $closedateObject = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row[5]);
                        $CloseDate = $closedateObject ? $closedateObject->format('Y-m-d H:i:s') : '-';
                    }
                } catch (\Throwable $th) {
                    $CaseCreateDate = '-';
                    $DisbursmentDate = '-';
                    $CloseDate = '-';
                }


                $new_rc = new RawData;
                $new_rc->dump_date = $this->data['dump_date'];
                $new_rc->batch_id = $this->data['batch_id'];
                $new_rc->company_id = $this->data['company_id'];
                $new_rc->client_id = $this->data['client_id'];
                $new_rc->partner_id = $this->data['partner_id'];
                $new_rc->process_id = $this->data['process_id'];
                $new_rc->partner_location_id = 44;

                // $new_rc->qa_id =  trim($row[18]);  // Ensure index exists

                $new_rc->qtl_id = Auth::user()->id;
                $new_rc->event_c = trim($row[0]);
                $new_rc->case_id = isset($row[1]) ? trim($row[1]) : null;
                $new_rc->case_create_date =  $CaseCreateDate;
                $new_rc->disbursment_date = $DisbursmentDate;
                $new_rc->diff = isset($row[4]) ? trim($row[4]) : null;
                $new_rc->close_date = $CloseDate;
                $new_rc->call_id = isset($row[6]) ? trim($row[6]) : null;
                $new_rc->customer_id = isset($row[7]) ? trim($row[7]) : null;
                $new_rc->relationship_number = isset($row[8]) ? trim($row[8]) : null;
                $new_rc->type_c = isset($row[9]) ? trim($row[9]) : null;
                $new_rc->sub_type = isset($row[10]) ? trim($row[10]) : null;
                $new_rc->category = isset($row[11]) ? trim($row[11]) : null;
                $new_rc->solution_based_category_c = isset($row[12]) ? trim($row[12]) : null;
                $new_rc->internal_case_reason_c = isset($row[13]) ? trim($row[13]) : null;
                $new_rc->disposition = isset($row[14]) ? trim($row[14]) : null;
                $new_rc->queue_name_c = isset($row[15]) ? trim($row[15]) : null;
                $new_rc->month = isset($row[16]) ? trim($row[16]) : null;
                $new_rc->week = trim($row[17]);
                $new_rc->hrs_tat_bucket = isset($row[18]) ? trim($row[18]) : null;
                $new_rc->user_name = isset($row[19]) ? trim($row[19]) : null;
                $new_rc->case_origin = isset($row[20]) ? trim($row[20]) : null;
                $new_rc->product_id = isset($row[21]) ? trim($row[21]) : null;
                $new_rc->product_business_family = isset($row[22]) ? trim($row[22]) : null;
                $new_rc->fpr = isset($row[23]) ? trim($row[23]) : null;
                $new_rc->case_origin_channel = isset($row[24]) ? trim($row[24]) : null;
                $new_rc->channel_master = isset($row[25]) ? trim($row[25]) : null;
                $new_rc->sla_manager = isset($row[26]) ? trim($row[26]) : null;
                $new_rc->agent_name = isset($row[27]) ? trim($row[27]) : null;
                $new_rc->agent_id = isset($row[28]) ? trim($row[28]) : null;
                $new_rc->partner_name = isset($row[29]) ? trim($row[29]) : null;
                $new_rc->location = isset($row[30]) ? trim($row[30]) : null;
                $new_rc->owner = isset($row[31]) ? trim($row[31]) : null;
                $new_rc->current_assigned_queue = isset($row[32]) ? trim($row[32]) : null;
                $new_rc->vertical = isset($row[33]) ? trim($row[33]) : null;
                $new_rc->case_reason_when_case_closed = isset($row[34]) ? trim($row[34]) : null;
                $new_rc->case_status = isset($row[35]) ? trim($row[35]) : null;
                $new_rc->qa_id = array_search(trim($row[36]), $this->all_qa);

                // Save the new record

                //   dd( $new_rc->qa_id);
                $new_rc->save();
            }
        } elseif ($this->data['process_id'] == 262 || $this->data['process_id'] == 272 || $this->data['process_id'] == 282 || $this->data['process_id'] == 269  || $this->data['process_id'] == 268 || $this->data['process_id'] == 270 || $this->data['process_id'] == 284   ) {
            if (isset($row[0]) && isset($row[0]) && $row[0] != 'Unique SR (Call Id)') {

                try {
                    // Convert open date
                    $CaseCreateDate = '-';
                    $CaseCloseDate = '-';

                    if (isset($row[9])) {
                        $opendateObject = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row[9]);
                        $CaseCreateDate = $opendateObject ? $opendateObject->format('Y-m-d H:i:s') : '-';
                    }
                    if (isset($row[10])) {
                        $closedateObject = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row[10]);
                        $CaseCloseDate = $closedateObject ? $closedateObject->format('Y-m-d H:i:s') : '-';
                    }
                } catch (\Throwable $th) {
                    $CaseCreateDate = '-';
                    $CaseCloseDate = '-';
                }

                $new_rc = new RawData;
                $new_rc->dump_date = $this->data['dump_date'];
                $new_rc->batch_id = $this->data['batch_id'];
                $new_rc->company_id = $this->data['company_id'];
                $new_rc->client_id = $this->data['client_id'];
                $new_rc->partner_id = $this->data['partner_id'];
                $new_rc->process_id = $this->data['process_id'];
                $new_rc->partner_location_id = array_search(trim($row[1]), $this->all_location);

                // $new_rc->qa_id =  trim($row[18]);  // Ensure index exists

                $new_rc->qtl_id = Auth::user()->id;


                $new_rc->case_create_date =  $CaseCreateDate;
                $new_rc->close_date =  $CaseCloseDate;
                $new_rc->call_id = isset($row[0]) ? trim($row[0]) : null;
                $new_rc->current_assigned_queue = isset($row[2]) ? trim($row[2]) : null;
                $new_rc->case_origin = isset($row[3]) ? trim($row[3]) : null;
                $new_rc->case_number = isset($row[4]) ? trim($row[4]) : null;
                $new_rc->relationship_number = isset($row[5]) ? trim($row[5]) : null;
                $new_rc->error_code_reason = isset($row[6]) ? trim($row[6]) : null; // complaint 
                $new_rc->error_code = isset($row[7]) ? trim($row[7]) : null; // Customer Redressal Decision
                $new_rc->case_id = isset($row[8]) ? trim($row[8]) : null;
                $new_rc->call_type = isset($row[11]) ? trim($row[11]) : null;
                $new_rc->call_sub_type = isset($row[12]) ? trim($row[12]) : null;
                $new_rc->case_reason = isset($row[13]) ? trim($row[13]) : null;
                $new_rc->disposition = isset($row[14]) ? trim($row[14]) : null;  //Case Disposition Name
                $new_rc->scenerio = isset($row[15]) ? trim($row[15]) : null;   //Customer Query
                $new_rc->case_origin_channel = isset($row[16]) ? trim($row[16]) : null;   //Case Owner
                $new_rc->case_status = isset($row[17]) ? trim($row[17]) : null;   //case status             
                $new_rc->qa_id = array_search(trim($row[18]), $this->all_qa);

                // Save the new record

                //   dd( $new_rc->qa_id);
                $new_rc->save();
            }
        } elseif (in_array($this->data['process_id'], [230, 231, 232, 233, 234, 235, 236, 237, 238, 239, 240, 241, 242, 243,271])) {
            if (isset($row[7]) && isset($row[8]) && $row[0] != 'Unit') {

                try {
                    // Convert open date
                    $CaseCreateDate = '-';


                    if (isset($row[2])) {
                        $opendateObject = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row[2]);
                        $CaseCreateDate = $opendateObject ? $opendateObject->format('Y-m-d H:i:s') : '-';
                    }
                } catch (\Throwable $th) {
                    $CaseCreateDate = '-';
                }


                $new_rc = new RawData;
                $new_rc->dump_date = $this->data['dump_date'];
                $new_rc->batch_id = $this->data['batch_id'];
                $new_rc->company_id = $this->data['company_id'];
                $new_rc->client_id = $this->data['client_id'];
                $new_rc->partner_id = $this->data['partner_id'];
                $new_rc->process_id = $this->data['process_id'];
                $new_rc->partner_location_id = array_search(trim($row[7]), $this->all_location);
                $new_rc->qa_id = array_search(trim($row[37]), $this->all_qa);
                // $new_rc->qa_id =  trim($row[18]);  // Ensure index exists

                $new_rc->qtl_id = Auth::user()->id;
                $new_rc->qrc_1 = trim($row[0]);
                $new_rc->bfl_process = trim($row[1]);
                $new_rc->opened_date = $CaseCreateDate;

                $new_rc->audit_type = trim($row[3]);
                $new_rc->agent_name = trim($row[4]);
                $new_rc->emp_id = trim($row[5]);
                $new_rc->auditor_name = trim($row[6]);

                $new_rc->partner_location_id = array_search(trim($row[7]), $this->all_location);
                $new_rc->location = trim($row[7]);

                $new_rc->call_id = trim($row[8]);
                $new_rc->reference_no = trim($row[9]);

                $new_rc->case_status = trim($row[10]);
                $new_rc->case_type = trim($row[11]);

                $new_rc->call_type = trim($row[12]);
                $new_rc->call_sub_type = trim($row[13]);

                $new_rc->case_reason = trim($row[14]);
                $new_rc->solution = trim($row[15]);

                $new_rc->campaign_name = trim($row[16]);
                $new_rc->customer_id = trim($row[17]);
                $new_rc->customer_name = trim($row[18]);
                $new_rc->brand_name = trim($row[19]);

                $new_rc->order_stage = trim($row[20]);
                $new_rc->sr_mgr = trim($row[21]);

                $new_rc->fpr = trim($row[22]);
                $new_rc->hrs_tat_bucket = trim($row[23]);

                $new_rc->info_1 = trim($row[24]);
                $new_rc->info_2 = trim($row[25]);
                $new_rc->info_3 = trim($row[26]);
                $new_rc->info_4 = trim($row[27]);
                $new_rc->info_5 = trim($row[28]);

                $new_rc->error_reason_type = trim($row[29]);
                $new_rc->error_code_reason = trim($row[30]);
                $new_rc->error_code = trim($row[31]);

                $new_rc->issues = trim($row[32]);
                $new_rc->sub_issues = trim($row[33]);

                $new_rc->case_reason_when_case_closed = trim($row[34]);

                $new_rc->product_id = trim($row[35]);
                $new_rc->solution_based_category_c = trim($row[36]);

                $new_rc->qa_id = array_search(trim($row[37]), $this->all_qa);


                // Save the new record

                //   dd( $new_rc->qa_id);
                $new_rc->save();
            }
        } 
        elseif (in_array($this->data['process_id'], [261, 263])) {
            if (isset($row[0]) && isset($row[1]) && $row[9] != 'Source') {

                try {
                    // Convert open date
                    $CaseCreateDate = '-';
                    $CaseCloseDate = '-';   // qc date


                    if (isset($row[3])) {
                        $opendateObject = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row[3]);
                        $CaseCreateDate = $opendateObject ? $opendateObject->format('Y-m-d H:i:s') : '-';
                    }
                    if (isset($row[4])) {
                        $closedateObject = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row[4]);
                        $CaseCloseDate = $closedateObject ? $closedateObject->format('Y-m-d H:i:s') : '-';
                    }
                } catch (\Throwable $th) {
                    $CaseCreateDate = '-';
                    $CaseCloseDate = '-';
                }


                $new_rc = new RawData;
                $new_rc->dump_date = $this->data['dump_date'];
                $new_rc->batch_id = $this->data['batch_id'];
                $new_rc->company_id = $this->data['company_id'];
                $new_rc->client_id = $this->data['client_id'];
                $new_rc->partner_id = $this->data['partner_id'];
                $new_rc->process_id = $this->data['process_id'];
                $new_rc->partner_location_id = array_search(trim($row[1]), $this->all_location);
                $new_rc->qa_id = array_search(trim($row[0]), $this->all_qa);
                // $new_rc->qa_id =  trim($row[18]);  // Ensure index exists

                $new_rc->qtl_id = Auth::user()->id;


                $new_rc->case_create_date = $CaseCreateDate;
                $new_rc->close_date = $CaseCloseDate;
                $new_rc->call_id = isset($row[2]) ? trim($row[2]) : null;
                $new_rc->emp_id = trim($row[5]);
                $new_rc->agent_name = trim($row[6]);
                $new_rc->bfl_tl_name = trim($row[7]);
                $new_rc->product_business_family = trim($row[8]);  // Platfrom  
                $new_rc->scenerio = trim($row[9]);  // source  
                $new_rc->case_reason = trim($row[10]);
                $new_rc->disposition = trim($row[11]);
                $new_rc->event_c = trim($row[12]);
                $new_rc->case_reason_when_case_closed = trim($row[13]);
                $new_rc->call_time = trim($row[14]);
                $new_rc->call_duration = trim($row[15]);
                $new_rc->case_number = trim($row[16]);
                $new_rc->reference_no = trim($row[17]);  //API No.





                // Save the new record

                //   dd( $new_rc->qa_id);
                $new_rc->save();
            }
        } 
         elseif (in_array($this->data['process_id'], [283])) {
            if (isset($row[0]) && isset($row[1]) && $row[0] != 'Employee ID') {

                try {
                    // Convert open date
                    $CaseCreateDate = '-';   // Action Date
                    $CaseCloseDate = '-';   // Monitor Date


                    if (isset($row[12])) {
                        $opendateObject = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row[12]);
                        $CaseCreateDate = $opendateObject ? $opendateObject->format('Y-m-d H:i:s') : '-';
                    }
                    if (isset($row[13])) {
                        $closedateObject = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row[13]);
                        $CaseCloseDate = $closedateObject ? $closedateObject->format('Y-m-d H:i:s') : '-';
                    }
                } catch (\Throwable $th) {
                    $CaseCreateDate = '-';
                    $CaseCloseDate = '-';
                }


                $new_rc = new RawData;
                $new_rc->dump_date = $this->data['dump_date'];
                $new_rc->batch_id = $this->data['batch_id'];
                $new_rc->company_id = $this->data['company_id'];
                $new_rc->client_id = $this->data['client_id'];
                $new_rc->partner_id = $this->data['partner_id'];
                $new_rc->process_id = $this->data['process_id'];
                $new_rc->partner_location_id = array_search(trim($row[2]), $this->all_location);
                $new_rc->qa_id = array_search(trim($row[14]), $this->all_qa);
                // $new_rc->qa_id =  trim($row[18]);  // Ensure index exists

                $new_rc->qtl_id = Auth::user()->id;


                $new_rc->case_create_date = $CaseCreateDate;   // action date
                $new_rc->close_date = $CaseCloseDate; // montior date
                $new_rc->emp_id = trim($row[0]);
                $new_rc->auditor_name = trim($row[1]);
                $new_rc->call_id = isset($row[3]) ? trim($row[3]) : null;
                $new_rc->reference_no = trim($row[4]);  // Loan acc number  
               
                $new_rc->case_origin_channel = trim($row[5]); // case owner
                $new_rc->case_origin = trim($row[6]); // case origin
                $new_rc->case_type = trim($row[7]); // case type
                $new_rc->call_type = trim($row[8]); // Type
                $new_rc->call_sub_type = trim($row[9]);  // sub type
                $new_rc->case_reason = trim($row[10]);  // Case Reason
                $new_rc->solution = trim($row[11]);  // Solution
         
                // $new_rc->case_number = trim($row[16]);
            

                // Save the new record

                //   dd( $new_rc->qa_id);
                $new_rc->save();
            }
        } 
        else {

            if (trim($row[0]) != null && trim($row[1]) != null && strpos(trim($row[15]), 'VLOOKUP') !== true) {

                if (array_search(trim($row[5]), $this->all_location) != null && array_search(trim($row[17]), $this->all_qa) != null) {
                    $new_rc = new RawData;
                    $new_rc->dump_date = $this->data['dump_date'];
                    $new_rc->batch_id = $this->data['batch_id'];
                    $new_rc->company_id = $this->data['company_id'];
                    $new_rc->client_id = $this->data['client_id'];
                    $new_rc->partner_id = $this->data['partner_id'];
                    $new_rc->process_id = $this->data['process_id'];
                    $new_rc->partner_location_id = array_search(trim($row[5]), $this->all_location);
                    $new_rc->qa_id = array_search(trim($row[17]), $this->all_qa);
                    $new_rc->agent_name = trim($row[0]);
                    if (array_search(trim($row[0]), $this->all_agents)) {
                        $agents = User::where('email', $row[0])->first();
                        $new_rc->agent_id = array_search(trim($row[0]), $this->all_agents);
                        $new_rc->vendor_name = $agents->vendor;
                        $new_rc->doj = $agents->doj;
                    }
                    $new_rc->qtl_id = Auth::user()->id;
                    $new_rc->call_id = trim($row[1]);
                    $new_rc->call_time = trim($row[2]);
                    $new_rc->call_duration = trim($row[3]);
                    $new_rc->phone_number = trim($row[4]);
                    $new_rc->location = trim($row[5]);
                    $new_rc->language = trim($row[6]);
                    $new_rc->call_type = trim($row[7]);
                    $new_rc->call_sub_type = trim($row[8]);
                    $new_rc->disposition = trim($row[9]);
                    $new_rc->campaign_name = trim($row[10]);
                    $new_rc->hangup_details = trim($row[11]);
                    $new_rc->lob = trim($row[12]);
                    $new_rc->tl = trim($row[13]);
                    $new_rc->doj = trim($row[14]);
                    $new_rc->emp_id = trim($row[15]);
                    $new_rc->customer_name = trim($row[16]);
                    $new_rc->brand_name = trim($row[18]);
                    $new_rc->circle = trim($row[19]);
                    $new_rc->info_1 = trim($row[20]);
                    $new_rc->info_2 = trim($row[21]);
                    $new_rc->info_3 = trim($row[22]);
                    $new_rc->info_4 = trim($row[23]);
                    $new_rc->info_5 = trim($row[24]);
                    // if(in_array($this->data['partner_id'], $partnerIds)){
                    //     if (array_search(trim($row[0]), $this->all_agents)) {
                    //         $new_rc->save();
                    //     } 
                    // }else {
                    //     $new_rc->save();
                    // }

                    $new_rc->save();
                } else {
                    $fr = new FailedRawDumpRow;
                    $fr->failed_raw_dump_slot_id = $this->data['batch_id'];
                    $fr->call_id = $row[1];
                    $fr->save();
                }
            }
        }
    }

    public function batchSize(): int
    {
        return 1000;
    }

    public function chunkSize(): int
    {
        return 250;
    }

    public function startRow(): int
    {
        return 2;
    }
}
