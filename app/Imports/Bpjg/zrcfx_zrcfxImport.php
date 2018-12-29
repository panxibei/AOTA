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
			
			'jizhongming' => $row['机种名'],
			'd1' => $row['d1'],
			'd2' => $row['d2'],
			'd3' => $row['d3'],
			'd4' => $row['d4'],
			'd5' => $row['d5'],
			'd6' => $row['d6'],
			'd7' => $row['d7'],
			'd8' => $row['d8'],
			'd9' => $row['d9'],
			'd10' => $row['d10'],
			'd11' => $row['d11'],
			'd12' => $row['d12'],
			'd13' => $row['d13'],
			'd14' => $row['d14'],
			'd15' => $row['d15'],
			'd16' => $row['d16'],
			'd17' => $row['d17'],
			'd18' => $row['d18'],
			'd19' => $row['d19'],
			'd20' => $row['d20'],
			'd21' => $row['d21'],
			'd22' => $row['d22'],
			'd23' => $row['d23'],
			'd24' => $row['d24'],
			'd25' => $row['d25'],
			'd26' => $row['d26'],
			'd27' => $row['d27'],
			'd28' => $row['d28'],
			'd29' => $row['d29'],
			'd30' => $row['d30'],
			'd31' => $row['d31'],
		]);
		
    }
}
