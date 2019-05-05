<?php

namespace App\Imports\Scgl;

use App\Models\Scgl\Scgl_hcfx_relation;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Imports\HeadingRowFormatter;

HeadingRowFormatter::default('none');

class hcfx_relationImport implements ToModel, WithHeadingRow
{
	
	//
    public function model(array $row)
    {
		// dd($row['日期']);
		// if (is_null($row['日期'])) {
			// return null;
		// }
	
		return new Scgl_hcfx_relation([
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
			'jizhongming' => substr($row['机种'], 0, 7),
			'tuopanxinghao' => $row['托盘型号'],
			'tai_per_tuo' => $row['台/托'] ?? 0,
			// 'zongshu' => is_null($row['总数']) ? 0 : $row['总数'],
			// 'zongshu' => 0,
			// 'shuliang' => is_null($row['数量']) ? 0 : $row['数量'],
			// 'shuliang' => 0,
		]);
		
    }
}
