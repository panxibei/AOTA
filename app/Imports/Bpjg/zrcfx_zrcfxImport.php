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
			
			'jizhongming' => substr($row['机种名'], 0, 8),
			'd1' => is_null($row['d1']) ? 0 : $row['d1'],
			'd2' => is_null($row['d2']) ? 0 : $row['d2'],
			'd3' => is_null($row['d3']) ? 0 : $row['d3'],
			'd4' => is_null($row['d4']) ? 0 : $row['d4'],
			'd5' => is_null($row['d5']) ? 0 : $row['d5'],
			'd6' => is_null($row['d6']) ? 0 : $row['d6'],
			'd7' => is_null($row['d7']) ? 0 : $row['d7'],
			'd8' => is_null($row['d8']) ? 0 : $row['d8'],
			'd9' => is_null($row['d9']) ? 0 : $row['d9'],
			'd10' => is_null($row['d10']) ? 0 : $row['d10'],
			'd11' => is_null($row['d11']) ? 0 : $row['d11'],
			'd12' => is_null($row['d12']) ? 0 : $row['d12'],
			'd13' => is_null($row['d13']) ? 0 : $row['d13'],
			'd14' => is_null($row['d14']) ? 0 : $row['d14'],
			'd15' => is_null($row['d15']) ? 0 : $row['d15'],
			'd16' => is_null($row['d16']) ? 0 : $row['d16'],
			'd17' => is_null($row['d17']) ? 0 : $row['d17'],
			'd18' => is_null($row['d18']) ? 0 : $row['d18'],
			'd19' => is_null($row['d19']) ? 0 : $row['d19'],
			'd20' => is_null($row['d20']) ? 0 : $row['d20'],
			'd21' => is_null($row['d21']) ? 0 : $row['d21'],
			'd22' => is_null($row['d22']) ? 0 : $row['d22'],
			'd23' => is_null($row['d23']) ? 0 : $row['d23'],
			'd24' => is_null($row['d24']) ? 0 : $row['d24'],
			'd25' => is_null($row['d25']) ? 0 : $row['d25'],
			'd26' => is_null($row['d26']) ? 0 : $row['d26'],
			'd27' => is_null($row['d27']) ? 0 : $row['d27'],
			'd28' => is_null($row['d28']) ? 0 : $row['d28'],
			'd29' => is_null($row['d29']) ? 0 : $row['d29'],
			'd30' => is_null($row['d30']) ? 0 : $row['d30'],
			'd31' => is_null($row['d31']) ? 0 : $row['d31'],
		]);
		
    }
}
