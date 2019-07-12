<?php

namespace App\Models\Smt;

use Illuminate\Database\Eloquent\Model;

class Smt_pdplanresult extends Model
{
	protected $fillable = [
        'suoshuriqi', 'xianti', 'banci', 'jizhongming', 'spno', 'pinming', 'lotshu', 'gongxu', 'jihuachanliang',
    ];
}
