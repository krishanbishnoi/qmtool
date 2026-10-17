<?php
namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;

class AgentQuartileExport implements FromCollection, WithHeadings
{
    use Exportable;

    Public $data;

    public function __construct(Array $data)
    {
        $this->data = $data;
    }

    public function collection()
    {
        //echo "<pre>";
        //print_r($this->data['data']); die;  
        return collect($this->data['data']);
    }

    public function headings(): array
    {
        return [
            'Emp_ID',
            'Name',
            'Audit Count',
            'Bucket',
            'Score'
        ];
    }   

}