<?php

namespace App\Models\Main;

use Illuminate\Database\Eloquent\Model;

class Release extends Model
{
	protected $fillable = [
        'title', 'content',
    ];
}
