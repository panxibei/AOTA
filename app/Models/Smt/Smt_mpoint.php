<?php

namespace App\Models\Smt;

use Illuminate\Database\Eloquent\Model;

class Smt_mpoint extends Model
{
	protected $fillable = [
        'jizhongming', 'pinming', 'gongxu', 'diantai', 'pinban',
    ];
}
