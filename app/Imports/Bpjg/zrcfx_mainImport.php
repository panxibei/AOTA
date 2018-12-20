<?php

namespace App\Imports\Bpjg;

use App\Models\Bpjg\Bpjg_zhongricheng_main;
use Maatwebsite\Excel\Concerns\ToModel;

class zrcfx_mainImport implements ToModel
{
	//
    public function model(array $row)
    {
		// dump($row);
		if (!isset($row[0])) {
			return null;
		}
	
		// Smt_qcreport::create([
		return new Bpjg_zhongricheng_main([
			'riqi' => $row[0],
			'xianti' => $row[1],
			'qufen' => $row[2],
			'jizhongming' => $row[3],
			'pinfan' => $row[4],
			'pinming' => $row[5],
			'leibie' => $row[6],
			'xuqiushuliang' => $row[7],
			'zongshu' => $row[8],
			'shuliang' => $row[9],
		]);
		
    }
}
