<?php

namespace App\Imports;

use App\Models\Smt\Smt_qcreport;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class qcreportImport implements ToCollection
{
    public function collection(Collection $sheets)
    {
		// dd($sheets);
		// dd($sheets->first());
        foreach ($sheets->first() as $row) 
        {
            // Qcreport::create([
                // 'name' => $row[0],
            // ]);
			// dd($row);
            Smt_qcreport::create([
                // 'name' => $row[0],
				// 'xianti' => $row[1],
				// 'banci' => $row[2],
				// 'jizhongming' => $row[3],
				// 'pinming' => $row[4],
				// 'gongxu' => $row[5],
				// 'spno' => $row[6],
				// 'lotshu' => $row[7],
				// 'dianmei' => $row[8],
				// 'meishu' => $row[9],
				// 'hejidianshu' => $row[10],
				// 'bushihejianshuheji' => $row[11],
				// 'ppm' => $row[12],
				// 'buliangneirong' => $row[13],
				// 'weihao' => $row[14],
				// 'shuliang' => $row[15],
				// 'jianchajileixing' => $row[16],
				// 'jianchazhe' => $row[17],
				'xianti' => 'SMT-1',
				'banci' => 'A-1',
				'jizhongming' => 'MRAP808A',
				'pinming' => 'MAIN',
				'gongxu' => 'B',
				'spno' => '5283600121-51',
				'lotshu' => '900',
				'dianmei' => '22',
				'meishu' => '3',
				'hejidianshu' => '66',
				'bushihejianshuheji' => '1',
				'ppm' => '15151.52',
				'buliangneirong' => '焊锡球',
				'weihao' => 'FDEF13',
				'shuliang' => '1',
				'jianchajileixing' => 'VQZ',
				'jianchazhe' => '蔡素英',
            ]);
        }
    }
	
	
}
