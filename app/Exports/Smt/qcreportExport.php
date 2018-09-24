<?php

namespace App\Exports\Smt;

use Maatwebsite\Excel\Concerns\FromCollection;
// use App\Models\Smt\Smt_qcreport;

use Illuminate\Support\Collection;

class qcreportExport implements FromCollection
{
	public function __construct($data){
		$this->data = $data;
	}
	
    public function collection()
    {
        // return User::all();
		
        return new Collection($this->data);
		
        // $cellData = [
            // ['学号','姓名','成绩'],
            // ['101','AAAAA', $this->id],
            // ['102','BBBBB','92'],
            // ['103','CCCCC','95'],
            // ['104','DDDDD','89'],
            // ['105','EEEEE','96'],
        // ];
		
        // return new Collection($cellData);
    }
}