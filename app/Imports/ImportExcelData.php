<?php

namespace App\Imports;
ini_set('memory_limit', '-1');
set_time_limit(0);
use App\FailedRawDumpRow;
use App\RawData;
use App\Audit;
use App\Region;
use App\QmSheet;
use App\Partner;
use App\Role;
use App\RoleUser;
use App\ScenerioTree;
use App\User;
use Auth;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithStartRow;



class ImportExcelData implements ToModel, WithBatchInserts, WithChunkReading, WithStartRow
{
    Public $data;
    Public $email;
  

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Array $data)
    {
        // $this->data = $data;
        // $this->email = User::pluck('email','id')->toArray();
    }
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        if(strpos($row[0], 'VLOOKUP') !== true){
            try {
               
                $rawData = RawData::where('call_id',  $row[0])->where('status', 1)->first();
                $rawData->location = $row[1];
                $rawData->save();
                // dd("done");
                
            } catch (\Exception $th) {
                dd($row[0], $th->getMessage());
            }
            
        }        
    }

    public function batchSize(): int
    {
        return 100;
    }
    
    public function chunkSize(): int
    {
        return 25;
    }

    public function startRow(): int
    {
        return 1;
    }
}
