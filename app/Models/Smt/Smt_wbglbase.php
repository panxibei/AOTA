<?php

namespace App\Models\Smt;

use Illuminate\Database\Eloquent\Model;

class Smt_wbglbase extends Model
{
	protected $fillable = [
        'jizhongming', 'pinming', 'xilie', 'wangbanbufan',
         'bianhao', 'wangbanhoudu', 'teshugongyi',
        

	];
	
	/**
     * 这个属性应该被转换为原生类型.
     * 用于json与array互相转换
     * @var array
     */
    protected $casts = [
        // 'buliangxinxi' => 'json',
    ];


}
