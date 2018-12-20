<?php

namespace App\Imports\Bpjg;

use App\Models\Bpjg\Bpjg_zhongricheng_zrc;
use Maatwebsite\Excel\Concerns\ToModel;

class zrcfx_zrcImport implements ToModel
{
	//
    public function model(array $row)
    {
		// dump($row);
		if (!isset($row[0])) {
			return null;
		}
	
		// Smt_qcreport::create([
		return new Bpjg_zhongricheng_zrc([
			'riqi' => $row[0],
			'jizhongming' => $row[1],
			'shuliang' => $row[2],
		]);
		
    }
}
