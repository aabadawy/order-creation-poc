<?php

namespace App\Models;

use App\Casts\Quantity;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\Pivot;

class OrderProductIngredient extends Pivot
{
    use HasFactory;

    protected $casts = [
        'quantity'  => Quantity::class
    ];
}
