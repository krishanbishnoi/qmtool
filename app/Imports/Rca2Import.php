<?php

namespace App\Imports;

use App\RcaTwo1;
use App\RcaTwo2;
use App\RcaTwo3;
use App\Rca2Mode;
// use Maatwebsite\Excel\Concerns\ToModel;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class Rca2Import implements ToCollection
{

    Public $data;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Array $data)
    {
        $this->data = $data;
    }
    /**
    * @param Collection $collection
    */
    public function collection(Collection $rows)
    {
        $placed_data = [];

        foreach ($rows as $row) 
        {
            if($row[0])
            {
                if($row[0]!='Mode')
                $placed_data[$row[0]]['data'][$row[1]]['data'][$row[2]]['data'][] = $row[3];
            }
        }
        //$placed_data = array_shift($placed_data);

        foreach ($placed_data as $key => $value) {
            $nr = new Rca2Mode;
            $nr->company_id = $this->data['company_id'];
            $nr->client_id = $this->data['client_id'];
            $nr->process_id = $this->data['process_id'];
            $nr->name = $key;
            $nr->save();
            $placed_data[$key]['row_id'] = $nr->id;
        }

        foreach ($placed_data as $key => $value) {
            foreach ($value['data'] as $keyb => $valueb) {
                if($keyb!='row_id')
                {
                    $nr = new RcaTwo1;
                    $nr->mode2_id = $value['row_id'];
                    $nr->name = $keyb;
                    $nr->save();
                    $placed_data[$key]['data'][$keyb]['row_id'] = $nr->id;          
                }
            }
        }

        foreach ($placed_data as $key => $value) {
            foreach ($value['data'] as $keyb => $valueb) {
                foreach ($valueb['data'] as $keyc => $valuec) {
                    if($keyc!='row_id')
                    {
                        $nr = new RcaTwo2;
                        $nr->rcatwo1_id = $valueb['row_id'];
                        $nr->name = $keyc;
                        $nr->save();
                        $placed_data[$key]['data'][$keyb]['data'][$keyc]['row_id'] = $nr->id;           
                    }
                }

            }
        }

        foreach ($placed_data as $key => $value) {
            foreach ($value['data'] as $keyb => $valueb) {
                foreach ($valueb['data'] as $keyc => $valuec) {
                    foreach ($valuec['data'] as $keyd => $valued) {
                        
                        
                            $nr = new RcaTwo3;
                            $nr->rcatwo2_id = $valuec['row_id'];
                            $nr->name = $valued;
                            $nr->save();
                        
                    }
                }
            }
        }
        // echo "<pre>";
        // print_r($placed_data);
        // echo "</pre>";

    }
    
}
