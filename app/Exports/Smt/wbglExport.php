<?php

namespace App\Exports\Smt;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Illuminate\Support\Collection;

class wbglExport implements FromCollection, WithStrictNullComparison, ShouldAutoSize
{
	public function __construct($data){
		$this->data = $data;
	}
	
    public function collection()
    {
        // return User::all();
		
        return new Collection($this->data);
		
        // $cellData = [
        //     ['学号','姓名','成绩'],
        //     ['101','AAAAA', 'qq'],
        //     [null,'BBBBB','92'],
        //     ['103','CCCCC','95'],
        //     ['104','DDDDD','89'],
        //     ['105','EEEEE','96'],
        // ];
		
        // return new Collection($cellData);
    }
}