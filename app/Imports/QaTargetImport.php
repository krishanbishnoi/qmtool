<?php

namespace App\Imports;

use App\User;
use Maatwebsite\Excel\Concerns\ToModel;
use Auth;


class QaTargetImport implements ToModel
{
    Public $all_qa;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->all_qa = User::where('company_id',Auth::user()->company_id)->whereHas('roles', function ($query) {
            $query->where('name', '=', 'qa');
        })->pluck('email','id')->toArray();
    }
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        if ($row[0] != null) {
            if(array_search($row[0],$this->all_qa)){
                $data = User::find(array_search($row[0],$this->all_qa));
                $data->qa_target = $row[1];
                $data->qa_target_date = $row[2];              
                $data->save();
            }
        }
    }
}