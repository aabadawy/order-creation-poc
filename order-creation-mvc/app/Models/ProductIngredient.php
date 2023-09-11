<?php

namespace App\Models;

use App\Casts\Quantity;
use App\ValueObjects\QuantityValueObject;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\Pivot;

/**
 * @property QuantityValueObject $quantity
 */
class ProductIngredient extends Pivot
{
    use HasFactory;

    protected $casts = [
        'quantity' => Quantity::class,
    ];
}
