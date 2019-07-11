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
			'suoshuyuefen' => $row['所属月份'] ?: '',
			// 'suoshuriqi' => is_null($row['所属日期']) ? '' : $row['所属日期'],
			'xianti' => $row['线体'] ?: '',
			'jizhongming' => $row['机种名'] ?: '',
			'spno' => $row['SPNO'] ?: '',
			'pinming' => $row['品名'] ?: '',
			'gongxu' => is_null($row['工序']) ? '' : substr($row['工序'], 1),
			'lotshu' => is_null($row['LOT数']) ? 0 : $lotshu[0],

			'd1_A' => $row['d1_A'] ?: 0,
			'd1_B' => $row['d1_B'] ?: 0,
			'd2_A' => $row['d2_A'] ?: 0,
			'd2_B' => $row['d2_B'] ?: 0,
			'd3_A' => $row['d3_A'] ?: 0,
			'd3_B' => $row['d3_B'] ?: 0,
			'd4_A' => $row['d4_A'] ?: 0,
			'd4_B' => $row['d4_B'] ?: 0,
			'd5_A' => $row['d5_A'] ?: 0,
			'd5_B' => $row['d5_B'] ?: 0,
			'd6_A' => $row['d6_A'] ?: 0,
			'd6_B' => $row['d6_B'] ?: 0,
			'd7_A' => $row['d7_A'] ?: 0,
			'd7_B' => $row['d7_B'] ?: 0,
			'd8_A' => $row['d8_A'] ?: 0,
			'd8_B' => $row['d8_B'] ?: 0,
			'd9_A' => $row['d9_A'] ?: 0,
			'd9_B' => $row['d9_B'] ?: 0,
			'd10_A' => $row['d10_A'] ?: 0,
			'd10_B' => $row['d10_B'] ?: 0,
			'd11_A' => $row['d11_A'] ?: 0,
			'd11_B' => $row['d11_B'] ?: 0,
			'd12_A' => $row['d12_A'] ?: 0,
			'd12_B' => $row['d12_B'] ?: 0,
			'd13_A' => $row['d13_A'] ?: 0,
			'd13_B' => $row['d13_B'] ?: 0,
			'd14_A' => $row['d14_A'] ?: 0,
			'd14_B' => $row['d14_B'] ?: 0,
			'd15_A' => $row['d15_A'] ?: 0,
			'd15_B' => $row['d15_B'] ?: 0,
			'd16_A' => $row['d16_A'] ?: 0,
			'd16_B' => $row['d16_B'] ?: 0,
			'd17_A' => $row['d17_A'] ?: 0,
			'd17_B' => $row['d17_B'] ?: 0,
			'd18_A' => $row['d18_A'] ?: 0,
			'd18_B' => $row['d18_B'] ?: 0,
			'd19_A' => $row['d19_A'] ?: 0,
			'd19_B' => $row['d19_B'] ?: 0,
			'd20_A' => $row['d20_A'] ?: 0,
			'd20_B' => $row['d20_B'] ?: 0,
			'd21_A' => $row['d21_A'] ?: 0,
			'd21_B' => $row['d21_B'] ?: 0,
			'd22_A' => $row['d22_A'] ?: 0,
			'd22_B' => $row['d22_B'] ?: 0,
			'd23_A' => $row['d23_A'] ?: 0,
			'd23_B' => $row['d23_B'] ?: 0,
			'd24_A' => $row['d24_A'] ?: 0,
			'd24_B' => $row['d24_B'] ?: 0,
			'd25_A' => $row['d25_A'] ?: 0,
			'd25_B' => $row['d25_B'] ?: 0,
			'd26_A' => $row['d26_A'] ?: 0,
			'd26_B' => $row['d26_B'] ?: 0,
			'd27_A' => $row['d27_A'] ?: 0,
			'd27_B' => $row['d27_B'] ?: 0,
			'd28_A' => $row['d28_A'] ?: 0,
			'd28_B' => $row['d28_B'] ?: 0,
			'd29_A' => $row['d29_A'] ?: 0,
			'd29_B' => $row['d29_B'] ?: 0,
			'd30_A' => $row['d30_A'] ?: 0,
			'd30_B' => $row['d30_B'] ?: 0,
			'd31_A' => $row['d31_A'] ?: 0,
			'd31_B' => $row['d31_B'] ?: 0,
		]);
		
    }
}
