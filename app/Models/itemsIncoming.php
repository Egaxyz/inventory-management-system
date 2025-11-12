<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class itemsIncoming extends Model
{
    protected $table = 'items_incoming';
    protected $fillable = [
        'product_id',
        'supplier_id',
        'user_id',
        'qty_incoming_items',
        'incoming_date',
    ];
    public $timestamps = false;

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'supplier_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}