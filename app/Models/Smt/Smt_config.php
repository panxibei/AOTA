<?php

namespace App\Models\Smt;

use Illuminate\Database\Eloquent\Model;

class Smt_config extends Model
{
	protected $fillable = [
        'name', 'value',
    ];
}
