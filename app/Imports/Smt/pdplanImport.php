<?php

namespace App\Imports\Smt;

use App\Models\Smt\Smt_pdplan;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Imports\HeadingRowFormatter;

HeadingRowFormatter::default('none');

class pdplanImport implements ToModel, WithHeadingRow
{
	//
    public function model(array $row)
    {
		// dump($row);
		// if (!isset($row[0])) {
			// return null;
		// }
	
		$lotshu = explode('/', $row['LOT数']);
		// dd($lotshu);
		// dd(is_null($row['d1_A']));
		// dd(is_null($row['d1_B']));
		// dd(is_null($row['d2_A']));
		// dd(is_null($row['d2_B']));
		// dd(is_null($row['d1_A']) ? 0 : $row['d1_A']);


		return new Smt_pdplan([
			'suoshuriqi' => is_null($row['所属日期']) ? '' : $row['所属日期'],
			'xianti' => is_null($row['线体']) ? '' : $row['线体'],
			'jizhongming' => is_null($row['机种名']) ? '' : $row['机种名'],
			'spno' => is_null($row['SPNO']) ? '' : $row['SPNO'],
			'pinming' => is_null($row['品名']) ? '' : $row['品名'],
			'gongxu' => is_null($row['工序']) ? '' : substr($row['工序'], 1),
			// 'lotshu' => is_null($row['LOT数']) ? '' : $row['LOT数'],
			'lotshu' => is_null($row['LOT数']) ? 0 : $lotshu[0],
			'd1_A' => is_null($row['d1_A']) ? 0 : $row['d1_A'],
			'd1_B' => is_null($row['d1_B']) ? 0 : $row['d1_B'],
			'd2_A' => is_null($row['d2_A']) ? 0 : $row['d2_A'],
			'd2_B' => is_null($row['d2_B']) ? 0 : $row['d2_B'],
			'd3_A' => is_null($row['d3_A']) ? 0 : $row['d3_A'],
			'd3_B' => is_null($row['d3_B']) ? 0 : $row['d3_B'],
			'd4_A' => is_null($row['d4_A']) ? 0 : $row['d4_A'],
			'd4_B' => is_null($row['d4_B']) ? 0 : $row['d4_B'],
			'd5_A' => is_null($row['d5_A']) ? 0 : $row['d5_A'],
			'd5_B' => is_null($row['d5_B']) ? 0 : $row['d5_B'],
			'd6_A' => is_null($row['d6_A']) ? 0 : $row['d6_A'],
			'd6_B' => is_null($row['d6_B']) ? 0 : $row['d6_B'],
			'd7_A' => is_null($row['d7_A']) ? 0 : $row['d7_A'],
			'd7_B' => is_null($row['d7_B']) ? 0 : $row['d7_B'],
			'd8_A' => is_null($row['d8_A']) ? 0 : $row['d8_A'],
			'd8_B' => is_null($row['d8_B']) ? 0 : $row['d8_B'],
			'd9_A' => is_null($row['d9_A']) ? 0 : $row['d9_A'],
			'd9_B' => is_null($row['d9_B']) ? 0 : $row['d9_B'],
			'd10_A' => is_null($row['d10_A']) ? 0 : $row['d10_A'],
			'd10_B' => is_null($row['d10_B']) ? 0 : $row['d10_B'],
			'd11_A' => is_null($row['d11_A']) ? 0 : $row['d11_A'],
			'd11_B' => is_null($row['d11_B']) ? 0 : $row['d11_B'],
			'd12_A' => is_null($row['d12_A']) ? 0 : $row['d12_A'],
			'd12_B' => is_null($row['d12_B']) ? 0 : $row['d12_B'],
			'd13_A' => is_null($row['d13_A']) ? 0 : $row['d13_A'],
			'd13_B' => is_null($row['d13_B']) ? 0 : $row['d13_B'],
			'd14_A' => is_null($row['d14_A']) ? 0 : $row['d14_A'],
			'd14_B' => is_null($row['d14_B']) ? 0 : $row['d14_B'],
			'd15_A' => is_null($row['d15_A']) ? 0 : $row['d15_A'],
			'd15_B' => is_null($row['d15_B']) ? 0 : $row['d15_B'],
			'd16_A' => is_null($row['d16_A']) ? 0 : $row['d16_A'],
			'd16_B' => is_null($row['d16_B']) ? 0 : $row['d16_B'],
			'd17_A' => is_null($row['d17_A']) ? 0 : $row['d17_A'],
			'd17_B' => is_null($row['d17_B']) ? 0 : $row['d17_B'],
			'd18_A' => is_null($row['d18_A']) ? 0 : $row['d18_A'],
			'd18_B' => is_null($row['d18_B']) ? 0 : $row['d18_B'],
			'd19_A' => is_null($row['d19_A']) ? 0 : $row['d19_A'],
			'd19_B' => is_null($row['d19_B']) ? 0 : $row['d19_B'],
			'd20_A' => is_null($row['d20_A']) ? 0 : $row['d20_A'],
			'd20_B' => is_null($row['d20_B']) ? 0 : $row['d20_B'],
			'd21_A' => is_null($row['d21_A']) ? 0 : $row['d21_A'],
			'd21_B' => is_null($row['d21_B']) ? 0 : $row['d21_B'],
			'd22_A' => is_null($row['d22_A']) ? 0 : $row['d22_A'],
			'd22_B' => is_null($row['d22_B']) ? 0 : $row['d22_B'],
			'd23_A' => is_null($row['d23_A']) ? 0 : $row['d23_A'],
			'd23_B' => is_null($row['d23_B']) ? 0 : $row['d23_B'],
			'd24_A' => is_null($row['d24_A']) ? 0 : $row['d24_A'],
			'd24_B' => is_null($row['d24_B']) ? 0 : $row['d24_B'],
			'd25_A' => is_null($row['d25_A']) ? 0 : $row['d25_A'],
			'd25_B' => is_null($row['d25_B']) ? 0 : $row['d25_B'],
			'd26_A' => is_null($row['d26_A']) ? 0 : $row['d26_A'],
			'd26_B' => is_null($row['d26_B']) ? 0 : $row['d26_B'],
			'd27_A' => is_null($row['d27_A']) ? 0 : $row['d27_A'],
			'd27_B' => is_null($row['d27_B']) ? 0 : $row['d27_B'],
			'd28_A' => is_null($row['d28_A']) ? 0 : $row['d28_A'],
			'd28_B' => is_null($row['d28_B']) ? 0 : $row['d28_B'],
			'd29_A' => is_null($row['d29_A']) ? 0 : $row['d29_A'],
			'd29_B' => is_null($row['d29_B']) ? 0 : $row['d29_B'],
			'd30_A' => is_null($row['d30_A']) ? 0 : $row['d30_A'],
			'd30_B' => is_null($row['d30_B']) ? 0 : $row['d30_B'],
			'd31_A' => is_null($row['d31_A']) ? 0 : $row['d31_A'],
			'd31_B' => is_null($row['d31_B']) ? 0 : $row['d31_B'],
		]);
		
    }
}
