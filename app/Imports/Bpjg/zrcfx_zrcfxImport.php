<?php

namespace App\Imports\Bpjg;

use App\Models\Bpjg\Bpjg_zhongricheng_zrcfx;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Imports\HeadingRowFormatter;

HeadingRowFormatter::default('none');

class zrcfx_zrcfxImport implements ToModel, WithHeadingRow
{
	//
    public function model(array $row)
    {
		// dump($row);
		// if (!isset($row[0])) {
			// return null;
		// }
	
		// Smt_qcreport::create([
		return new Bpjg_zhongricheng_zrcfx([
			// 'riqi' => $row[0],
			// 'jizhongming' => $row[1],
			// 'shuliang' => $row[2],
			
			'riqi' => $row['日期*'],
			'jizhongming' => $row['机种名*'],
			'shuliang' => $row['数量*'],
		]);
		
    }
}
