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

		$d1_A = $row['d1_A'] ? '01' . '_A_' . $row['d1_A'] : 0;
		$d1_B = $row['d1_B'] ? '01' . '_B_' . $row['d1_B'] : 0;
		$d2_A = $row['d2_A'] ? '02' . '_A_' . $row['d2_A'] : 0;
		$d2_B = $row['d2_B'] ? '02' . '_B_' . $row['d2_B'] : 0;
		$d3_A = $row['d3_A'] ? '03' . '_A_' . $row['d3_A'] : 0;
		$d3_B = $row['d3_B'] ? '03' . '_B_' . $row['d3_B'] : 0;
		$d4_A = $row['d4_A'] ? '04' . '_A_' . $row['d4_A'] : 0;
		$d4_B = $row['d4_B'] ? '04' . '_B_' . $row['d4_B'] : 0;
		$d5_A = $row['d5_A'] ? '05' . '_A_' . $row['d5_A'] : 0;
		$d5_B = $row['d5_B'] ? '05' . '_B_' . $row['d5_B'] : 0;
		$d6_A = $row['d6_A'] ? '06' . '_A_' . $row['d6_A'] : 0;
		$d6_B = $row['d6_B'] ? '06' . '_B_' . $row['d6_B'] : 0;
		$d7_A = $row['d7_A'] ? '07' . '_A_' . $row['d7_A'] : 0;
		$d7_B = $row['d7_B'] ? '07' . '_B_' . $row['d7_B'] : 0;
		$d8_A = $row['d8_A'] ? '08' . '_A_' . $row['d8_A'] : 0;
		$d8_B = $row['d8_B'] ? '08' . '_B_' . $row['d8_B'] : 0;
		$d9_A = $row['d9_A'] ? '09' . '_A_' . $row['d9_A'] : 0;
		$d9_B = $row['d9_B'] ? '09' . '_B_' . $row['d9_B'] : 0;
		$d10_A = $row['d10_A'] ? '10' . '_A_' . $row['d10_A'] : 0;
		$d10_B = $row['d10_B'] ? '10' . '_B_' . $row['d10_B'] : 0;
		$d11_A = $row['d11_A'] ? '11' . '_A_' . $row['d11_A'] : 0;
		$d11_B = $row['d11_B'] ? '11' . '_B_' . $row['d11_B'] : 0;
		$d12_A = $row['d12_A'] ? '12' . '_A_' . $row['d12_A'] : 0;
		$d12_B = $row['d12_B'] ? '12' . '_B_' . $row['d12_B'] : 0;
		$d13_A = $row['d13_A'] ? '13' . '_A_' . $row['d13_A'] : 0;
		$d13_B = $row['d13_B'] ? '13' . '_B_' . $row['d13_B'] : 0;
		$d14_A = $row['d14_A'] ? '14' . '_A_' . $row['d14_A'] : 0;
		$d14_B = $row['d14_B'] ? '14' . '_B_' . $row['d14_B'] : 0;
		$d15_A = $row['d15_A'] ? '15' . '_A_' . $row['d15_A'] : 0;
		$d15_B = $row['d15_B'] ? '15' . '_B_' . $row['d15_B'] : 0;
		$d16_A = $row['d16_A'] ? '16' . '_A_' . $row['d16_A'] : 0;
		$d16_B = $row['d16_B'] ? '16' . '_B_' . $row['d16_B'] : 0;
		$d17_A = $row['d17_A'] ? '17' . '_A_' . $row['d17_A'] : 0;
		$d17_B = $row['d17_B'] ? '17' . '_B_' . $row['d17_B'] : 0;
		$d18_A = $row['d18_A'] ? '18' . '_A_' . $row['d18_A'] : 0;
		$d18_B = $row['d18_B'] ? '18' . '_B_' . $row['d18_B'] : 0;
		$d19_A = $row['d19_A'] ? '19' . '_A_' . $row['d19_A'] : 0;
		$d19_B = $row['d19_B'] ? '19' . '_B_' . $row['d19_B'] : 0;
		$d20_A = $row['d20_A'] ? '20' . '_A_' . $row['d20_A'] : 0;
		$d20_B = $row['d20_B'] ? '20' . '_B_' . $row['d20_B'] : 0;
		$d21_A = $row['d21_A'] ? '21' . '_A_' . $row['d21_A'] : 0;
		$d21_B = $row['d21_B'] ? '21' . '_B_' . $row['d21_B'] : 0;
		$d22_A = $row['d22_A'] ? '22' . '_A_' . $row['d22_A'] : 0;
		$d22_B = $row['d22_B'] ? '22' . '_B_' . $row['d22_B'] : 0;
		$d23_A = $row['d23_A'] ? '23' . '_A_' . $row['d23_A'] : 0;
		$d23_B = $row['d23_B'] ? '23' . '_B_' . $row['d23_B'] : 0;
		$d24_A = $row['d24_A'] ? '24' . '_A_' . $row['d24_A'] : 0;
		$d24_B = $row['d24_B'] ? '24' . '_B_' . $row['d24_B'] : 0;
		$d25_A = $row['d25_A'] ? '25' . '_A_' . $row['d25_A'] : 0;
		$d25_B = $row['d25_B'] ? '25' . '_B_' . $row['d25_B'] : 0;
		$d26_A = $row['d26_A'] ? '26' . '_A_' . $row['d26_A'] : 0;
		$d26_B = $row['d26_B'] ? '26' . '_B_' . $row['d26_B'] : 0;
		$d27_A = $row['d27_A'] ? '27' . '_A_' . $row['d27_A'] : 0;
		$d27_B = $row['d27_B'] ? '27' . '_B_' . $row['d27_B'] : 0;
		$d28_A = $row['d28_A'] ? '28' . '_A_' . $row['d28_A'] : 0;
		$d28_B = $row['d28_B'] ? '28' . '_B_' . $row['d28_B'] : 0;
		$d29_A = $row['d29_A'] ? '29' . '_A_' . $row['d29_A'] : 0;
		$d29_B = $row['d29_B'] ? '29' . '_B_' . $row['d29_B'] : 0;
		$d30_A = $row['d30_A'] ? '30' . '_A_' . $row['d30_A'] : 0;
		$d30_B = $row['d30_B'] ? '30' . '_B_' . $row['d30_B'] : 0;
		$d31_A = $row['d31_A'] ? '31' . '_A_' . $row['d31_A'] : 0;
		$d31_B = $row['d31_B'] ? '31' . '_B_' . $row['d31_B'] : 0;
		
		$chanliangxinxi = $d1_A . '|' . $d1_B . '|' . $d2_A . '|' . $d2_B . '|' . $d3_A . '|' . $d3_B . '|' . $d4_A . '|' . $d4_B . '|' . $d5_A . '|' . $d5_B
			. '|' . $d6_A . '|' . $d6_B . '|' . $d7_A . '|' . $d7_B . '|' . $d8_A . '|' . $d8_B . '|' . $d9_A . '|' . $d9_B . '|' . $d10_A . '|' . $d10_B
			. '|' . $d11_A . '|' . $d11_B . '|' . $d12_A . '|' . $d12_B . '|' . $d13_A . '|' . $d13_B . '|' . $d14_A . '|' . $d14_B . '|' . $d15_A . '|' . $d15_B
			. '|' . $d16_A . '|' . $d16_B . '|' . $d17_A . '|' . $d17_B . '|' . $d18_A . '|' . $d18_B . '|' . $d19_A . '|' . $d19_B . '|' . $d20_A . '|' . $d20_B
			. '|' . $d21_A . '|' . $d21_B . '|' . $d22_A . '|' . $d22_B . '|' . $d23_A . '|' . $d23_B . '|' . $d24_A . '|' . $d24_B . '|' . $d25_A . '|' . $d25_B
			. '|' . $d26_A . '|' . $d26_B . '|' . $d27_A . '|' . $d27_B . '|' . $d28_A . '|' . $d28_B . '|' . $d29_A . '|' . $d29_B . '|' . $d30_A . '|' . $d30_B . '|' . $d31_A . '|' . $d31_B;
// dd($chanliangxinxi);

		return new Smt_pdplan([
			'suoshuyuefen' => $row['所属月份'] ?: '',
			// 'suoshuriqi' => is_null($row['所属日期']) ? '' : $row['所属日期'],
			'xianti' => $row['线体'] ?: '',
			'jizhongming' => $row['机种名'] ?: '',
			'spno' => $row['SPNO'] ?: '',
			'pinming' => $row['品名'] ?: '',
			'gongxu' => is_null($row['工序']) ? '' : substr($row['工序'], 1),
			'lotshu' => is_null($row['LOT数']) ? 0 : $lotshu[0],

			'chanliangxinxi' => $chanliangxinxi,


			// 'd1_A' => $row['d1_A'] ?: 0,
			// 'd1_B' => $row['d1_B'] ?: 0,
			// 'd2_A' => $row['d2_A'] ?: 0,
			// 'd2_B' => $row['d2_B'] ?: 0,
			// 'd3_A' => $row['d3_A'] ?: 0,
			// 'd3_B' => $row['d3_B'] ?: 0,
			// 'd4_A' => $row['d4_A'] ?: 0,
			// 'd4_B' => $row['d4_B'] ?: 0,
			// 'd5_A' => $row['d5_A'] ?: 0,
			// 'd5_B' => $row['d5_B'] ?: 0,
			// 'd6_A' => $row['d6_A'] ?: 0,
			// 'd6_B' => $row['d6_B'] ?: 0,
			// 'd7_A' => $row['d7_A'] ?: 0,
			// 'd7_B' => $row['d7_B'] ?: 0,
			// 'd8_A' => $row['d8_A'] ?: 0,
			// 'd8_B' => $row['d8_B'] ?: 0,
			// 'd9_A' => $row['d9_A'] ?: 0,
			// 'd9_B' => $row['d9_B'] ?: 0,
			// 'd10_A' => $row['d10_A'] ?: 0,
			// 'd10_B' => $row['d10_B'] ?: 0,
			// 'd11_A' => $row['d11_A'] ?: 0,
			// 'd11_B' => $row['d11_B'] ?: 0,
			// 'd12_A' => $row['d12_A'] ?: 0,
			// 'd12_B' => $row['d12_B'] ?: 0,
			// 'd13_A' => $row['d13_A'] ?: 0,
			// 'd13_B' => $row['d13_B'] ?: 0,
			// 'd14_A' => $row['d14_A'] ?: 0,
			// 'd14_B' => $row['d14_B'] ?: 0,
			// 'd15_A' => $row['d15_A'] ?: 0,
			// 'd15_B' => $row['d15_B'] ?: 0,
			// 'd16_A' => $row['d16_A'] ?: 0,
			// 'd16_B' => $row['d16_B'] ?: 0,
			// 'd17_A' => $row['d17_A'] ?: 0,
			// 'd17_B' => $row['d17_B'] ?: 0,
			// 'd18_A' => $row['d18_A'] ?: 0,
			// 'd18_B' => $row['d18_B'] ?: 0,
			// 'd19_A' => $row['d19_A'] ?: 0,
			// 'd19_B' => $row['d19_B'] ?: 0,
			// 'd20_A' => $row['d20_A'] ?: 0,
			// 'd20_B' => $row['d20_B'] ?: 0,
			// 'd21_A' => $row['d21_A'] ?: 0,
			// 'd21_B' => $row['d21_B'] ?: 0,
			// 'd22_A' => $row['d22_A'] ?: 0,
			// 'd22_B' => $row['d22_B'] ?: 0,
			// 'd23_A' => $row['d23_A'] ?: 0,
			// 'd23_B' => $row['d23_B'] ?: 0,
			// 'd24_A' => $row['d24_A'] ?: 0,
			// 'd24_B' => $row['d24_B'] ?: 0,
			// 'd25_A' => $row['d25_A'] ?: 0,
			// 'd25_B' => $row['d25_B'] ?: 0,
			// 'd26_A' => $row['d26_A'] ?: 0,
			// 'd26_B' => $row['d26_B'] ?: 0,
			// 'd27_A' => $row['d27_A'] ?: 0,
			// 'd27_B' => $row['d27_B'] ?: 0,
			// 'd28_A' => $row['d28_A'] ?: 0,
			// 'd28_B' => $row['d28_B'] ?: 0,
			// 'd29_A' => $row['d29_A'] ?: 0,
			// 'd29_B' => $row['d29_B'] ?: 0,
			// 'd30_A' => $row['d30_A'] ?: 0,
			// 'd30_B' => $row['d30_B'] ?: 0,
			// 'd31_A' => $row['d31_A'] ?: 0,
			// 'd31_B' => $row['d31_B'] ?: 0,
		]);
		
    }
}
