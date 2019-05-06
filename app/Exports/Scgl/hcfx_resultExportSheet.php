<?php

namespace App\Exports\Scgl;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;
use Maatwebsite\Excel\Concerns\WithTitle;
use Illuminate\Support\Collection;

class hcfx_resultExportSheet implements FromCollection, WithStrictNullComparison, WithTitle
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
     * @return string
     * 多个sheet表单的title最好各不相同，如sheet1、sheet2、sheet3，可能需要通过不同的参数（如日期）等方式传递
     */
    public function title(): string
    {
        return '耗材分析（' . $this->suoshuriqi . '）';
    }


}
