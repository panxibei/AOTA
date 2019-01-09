<?php

namespace App\Exports\Bpjg;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;
use Illuminate\Support\Collection;

class zrcfx_relationExport implements FromCollection, WithStrictNullComparison
{
	
	public function __construct($data){
		$this->data = $data;
	}
	
    /**
    * @return \Illuminate\Support\Collection
    */
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
