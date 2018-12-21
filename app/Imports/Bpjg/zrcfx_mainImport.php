<?php

namespace App\Imports\Bpjg;

use App\Models\Bpjg\Bpjg_zhongricheng_main;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class zrcfx_mainImport implements ToModel, WithHeadingRow
{
	//
    public function model(array $row)
    {
		// dump($row);
		// if (!isset($row['日期'])) {
			// return null;
		// }
	
		// Smt_qcreport::create([
		return new Bpjg_zhongricheng_main([
			// 'riqi' => $row[0],
			// 'xianti' => $row[1],
			// 'qufen' => $row[2],
			// 'jizhongming' => $row[3],
			// 'pinfan' => $row[4],
			// 'pinming' => $row[5],
			// 'leibie' => $row[6],
			// 'xuqiushuliang' => $row[7],
			// 'zongshu' => $row[8],
			// 'shuliang' => $row[9],

			'riqi' => $row['riqi'],
			'xianti' => $row['线体'],
			'qufen' => $row['区分'],
			'jizhongming' => $row['机种名'],
			'pinfan' => $row['品番'],
			'pinming' => $row['品名'],
			'leibie' => $row['类别'],
			'xuqiushuliang' => $row['需求数量'],
			'zongshu' => $row['总数'],
			'shuliang' => $row['数量'],
		]);
		
    }
}
