<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Type extends Model
{
    protected $table = 'type';
    protected $fillable = [
        'type_code',
        'type_name',
        'description',
    ];
    public $timestamps = false;
}