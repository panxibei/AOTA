<?php

namespace App\Exports\Scgl;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\WithTitle;
use Illuminate\Support\Collection;

class hcfx_resultExport implements FromCollection, WithStrictNullComparison, WithTitle
{
	
	public function __construct($data, $suoshuriqi){
		$this->data = $data;
		$this->suoshuriqi = $suoshuriqi;
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

    /**
     * @return array
     */
    public function sheets(): array
    {
        $sheets = [];

        // for ($month = 1; $month <= 12; $month++) {
        //     $sheets[] = new InvoicesPerMonthSheet($this->year, $month);
        // }
        // $sheets[] = ['2019-10', '2019-11'];
        $sheets[] = [
            ['学号','姓名','成绩'],
            ['101','AAAAA','99'],
            ['102','BBBBB','92'],
            ['103','CCCCC','95'],
            ['104','DDDDD','89'],
            ['105','EEEEE','96'],
        ];


        return $sheets;
    }

    /**
     * @return string
     */
    public function title(): string
    {
        return $this->suoshuriqi;
    }


}
