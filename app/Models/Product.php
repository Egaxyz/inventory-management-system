<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $table = 'product';
    protected $fillable = [
        'type_id',
        'product_code',
        'product_name',
        'product_status',
        'qty',
        'price',
        'image',
        'description',
    ];
    public $timestamps = false;

    public function type()
    {
        return $this->belongsTo(Type::class, 'type_id', 'id');
    }
}