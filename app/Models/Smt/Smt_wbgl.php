<?php

namespace App\Models\Smt;

use Illuminate\Database\Eloquent\Model;

class Smt_wbgl extends Model
{
	protected $fillable = [
        'jizhongming', 'pinming', 'gongxu', 'xilie', 'wangbanbufan',
         'bianhao', 'wangbanhoudu', 'teshugongyi',
        'zhangli1', 'zhangli2', 'zhangli3', 'zhangli4',  'zhangli5', 
        'luruzhe', 'bianjizhe',

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
