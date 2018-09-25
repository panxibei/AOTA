<?php

namespace App\Imports;

use App\Models\Smt\Smt_qcreport;
// use Illuminate\Support\Collection;
// use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;
// use Maatwebsite\Excel\Concerns\WithHeadingRow;

class qcreportImport implements ToModel
{
    public function model(array $row)
    {
		// dump($row);
		// if (!isset($row[0])) {
			// return null;
		// }
	
		
		// Smt_qcreport::create([
		return new Smt_qcreport([
			// 'xianti' => $row['xianti'],
			// 'banci' => $row['banci'],
			// 'jizhongming' => $row['jizhongming'],
			// 'pinming' => $row['pinming'],
			// 'gongxu' => $row['gongxu'],
			// 'spno' => $row['spno'],
			// 'lotshu' => $row['lotshu'],
			// 'dianmei' => $row['dianmei'],
			// 'meishu' => $row['meishu'],
			// 'hejidianshu' => $row['hejidianshu'],
			// 'bushihejianshuheji' => $row['bushihejianshuheji'],
			// 'ppm' => $row['ppm'],
			// 'buliangneirong' => $row['buliangneirong'],
			// 'weihao' => $row['weihao'],
			// 'shuliang' => $row['shuliang'],
			// 'jianchajileixing' => $row['jianchajileixing'],
			// 'jianchazhe' => $row['jianchazhe'],
			// 'created_at' => $row['created_at'],
			// 'updated_at' => $row['updated_at'],
			
			'xianti' => $row[0],
			'banci' => $row[1],
			'jizhongming' => $row[2],
			'pinming' => $row[3],
			'gongxu' => $row[4],
			'spno' => $row[5],
			'lotshu' => $row[6],
			'dianmei' => $row[7],
			'meishu' => $row[8],
			'hejidianshu' => $row[9],
			'bushihejianshuheji' => $row[10],
			'ppm' => $row[11],
			'buliangneirong' => $row[12],
			'weihao' => $row[13],
			'shuliang' => $row[14],
			'jianchajileixing' => $row[15],
			'jianchazhe' => $row[16],
			'created_at' => $row[17],
			'updated_at' => $row[18],
			
			// 'xianti' => 'SMT-1',
			// 'banci' => 'A-1',
			// 'jizhongming' => 'MRAP808A',
			// 'pinming' => 'MAIN',
			// 'gongxu' => 'B',
			// 'spno' => '5283600121-51',
			// 'lotshu' => '900',
			// 'dianmei' => '22',
			// 'meishu' => '3',
			// 'hejidianshu' => '66',
			// 'bushihejianshuheji' => '1',
			// 'ppm' => '15151.52',
			// 'buliangneirong' => '焊锡球',
			// 'weihao' => 'FDEF13',
			// 'shuliang' => '1',
			// 'jianchajileixing' => 'VQZ',
			// 'jianchazhe' => '蔡素英',
		]);
		
    }
}













	
	
