<?php

namespace App\Models\Smt;

use Illuminate\Database\Eloquent\Model;

class Qcreport extends Model
{
	protected $fillable = [
        'xianti', 'banci', 'jizhongming', 'pinming', 'gongxu', 'spno', 'lotshu', 'dianmei', 'meishu',
		'hejidianshu', 'bushihejianshuheji', 'ppm', 
		'jianchajileixing', 'buliangneirong', 'weihao', 'shuliang', 'jianchazhe',
    ];
}
