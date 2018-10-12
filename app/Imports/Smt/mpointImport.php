<?php

namespace App\Imports\Smt;

use App\Models\Smt\Smt_mpoint;
use Maatwebsite\Excel\Concerns\ToModel;

class mpointImport implements ToModel
{
	//
    public function model(array $row)
    {
		// dump($row);
		if (!isset($row[0])) {
			return null;
		}
	
		// Smt_qcreport::create([
		return new Smt_mpoint([
			'jizhongming' => $row[0],
			'pinming' => $row[1],
			'gongxu' => $row[2],
			'diantai' => $row[3],
			'pinban' => $row[4],
		]);
		
    }
}
