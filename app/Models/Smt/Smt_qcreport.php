<?php

namespace App\Models\Smt;

use Illuminate\Database\Eloquent\Model;

class Smt_qcreport extends Model
{
	protected $fillable = [
        'jianchariqi', 'xianti', 'banci', 'jizhongming', 'pinming', 'gongxu', 'spno', 'lotshu', 'dianmei', 'meishu',
		'hejidianshu', 'bushihejianshuheji', 'ppm', 
		'jianchajileixing', 'buliangneirong', 'weihao', 'shuliang', 'jianchazhe',
		'buliangxinxi',
	];
	
	/**
     * 这个属性应该被转换为原生类型.
     * 用于json与array互相转换
     * @var array
     */
    protected $casts = [
        'buliangxinxi' => 'json',
    ];


}
