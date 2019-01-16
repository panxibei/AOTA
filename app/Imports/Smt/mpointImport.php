<?php

namespace App\Imports\Smt;

use App\Models\Smt\Smt_mpoint;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Imports\HeadingRowFormatter;

HeadingRowFormatter::default('none');

class mpointImport implements ToModel, WithHeadingRow
{
	//
    public function model(array $row)
    {
		// dump($row);
		// if (!isset($row[0])) {
			// return null;
		// }
	
		// Smt_qcreport::create([
		return new Smt_mpoint([
			'jizhongming' => is_null($row['机种名']) ? '' : $row['机种名'],
			'pinming' => is_null($row['品名']) ? '' : $row['品名'],
			'gongxu' => is_null($row['工序']) ? '' : $row['工序'],
			'diantai' => is_null($row['点/台']) ? '' : $row['点/台'],
			'pinban' => is_null($row['拼板']) ? '' : $row['拼板'],
		]);
		
    }
}
