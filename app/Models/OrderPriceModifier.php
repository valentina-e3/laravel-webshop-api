<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderPriceModifier extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'modifier_id',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function modifier()
    {
        return $this->belongsTo(PriceModifier::class);
    }
}
