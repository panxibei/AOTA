<?php

namespace App\Models\Smt;

use Illuminate\Database\Eloquent\Model;

class Mpoint extends Model
{
	protected $fillable = [
        'jizhongming', 'pinming', 'mian', 'diantai', 'pinban',
    ];
}
