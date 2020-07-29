<?php
// 此模块好像未使用
namespace App\Imports\Smt;

use App\Models\Smt\Smt_wbglbase;
// use Illuminate\Support\Collection;
// use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Imports\HeadingRowFormatter;

HeadingRowFormatter::default('none');

class wbglbaseImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
		// dump($row);
		// if (!isset($row[0])) {
			// return null;
		// }
	
		
		// Smt_qcreport::create([
		return new Smt_wbglbase([

			'wangbanbufan' => $row['网板部番'] ?: '',
			'jizhongming' => $row['机种名'] ?: '',
			'pinming' => $row['品名'] ?: '',
			'xilie' => $row['系列'] ?: '',
			'bianhao' => $row['编号'] ?: '',
			'wangbanhoudu' => $row['网板厚度'] ?: '',
			'teshugongyi' => $row['特殊工艺'] ?: '',
			
		]);
		
    }
}













	
	
