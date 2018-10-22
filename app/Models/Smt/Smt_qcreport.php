<?php

namespace App\Models\Smt;

use Illuminate\Database\Eloquent\Model;

class Smt_qcreport extends Model
{
	protected $fillable = [
        'shengchanriqi', 'xianti', 'banci', 'jizhongming', 'pinming', 'gongxu', 'spno', 'lotshu', 'dianmei', 'meishu',
		'hejidianshu', 'bushihejianshuheji', 'ppm', 
		'jianchajileixing', 'buliangneirong', 'weihao', 'shuliang', 'jianchazhe',
    ];
}
