<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PriceModifier extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'type',
        'value',
        'amount_threshold',
        'quantity_threshold',
        'is_percentage',
        'is_active',
        'apply_to_order',
    ];
}
