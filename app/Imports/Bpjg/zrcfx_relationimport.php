<?php

namespace App\Imports\Bpjg;

use App\Models\Bpjg\Bpjg_zhongricheng_relation;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Imports\HeadingRowFormatter;

HeadingRowFormatter::default('none');

class zrcfx_relationImport implements ToModel, WithHeadingRow
{
	
	//
    public function model(array $row)
    {
		// dd($row['日期']);
		// if (is_null($row['日期'])) {
			// return null;
		// }
	
		return new Bpjg_zhongricheng_relation([
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

			// 'riqi' => $row['日期'],
			'jizhongming' => $row['机种名'],
			'pinfan' => $row['品番'],
			'pinming' => $row['品名'],
			'xuqiushuliang' => $row['需求数量'] ?: 0,
			'leibie' => $row['类别'],
			// 'zongshu' => is_null($row['总数']) ? 0 : $row['总数'],
			// 'zongshu' => 0,
			// 'shuliang' => is_null($row['数量']) ? 0 : $row['数量'],
			// 'shuliang' => 0,
		]);
		
    }
}
