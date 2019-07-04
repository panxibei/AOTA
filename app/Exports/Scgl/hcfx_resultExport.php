<?php

namespace App\Exports\Scgl;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class hcfx_resultExport implements WithMultipleSheets, ShouldAutoSize
{
    use Exportable;

	public function __construct ($data, $suoshuriqi) {
		$this->data = $data;
		$this->suoshuriqi = $suoshuriqi;
	}
	
    /**
     * @return array
     */
    public function sheets(): array
    {
        $sheets = [];

        // for ($month = 1; $month <= 12; $month++) {
        //     $sheets[] = new InvoicesPerMonthSheet($this->year, $month);
        // }

        // 1. $sheets[] 有几个，就有几个sheet表单，每个表单的内容为调用各集合收集数据
        // 2. hcfx_resultExportSheet文件中的title最好是各不相同的文字
        // $sheets[] = new hcfx_resultExportSheet($this->data, $this->suoshuriqi);
        // $sheets[] = new hcfx_resultExportSheet($this->data, $this->suoshuriqi);
        $sheets[] = new hcfx_resultExportSheet($this->data, $this->suoshuriqi);

        return $sheets;
    }


}
