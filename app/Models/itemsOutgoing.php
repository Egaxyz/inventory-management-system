<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class itemsOutgoing extends Model
{
    protected $table = 'items_outgoing';
    protected $fillable = [
        'product_id',
        'user_id',
        'qty_outgoing_items',
        'outgoing_date',
    ];
    public $timestamps = false;

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}