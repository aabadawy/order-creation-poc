<?php

namespace App\Models;

use App\Casts\Quantity;
use App\ValueObjects\QuantityValueObject;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

/**
 * @property QuantityValueObject $quantity
 * @property Ingredient $ingredient
 * @property int $product_id
 * @property int $ingredient_id
 */
class ProductIngredient extends Pivot
{
    use HasFactory;

    protected $casts = [
        'quantity' => Quantity::class,
    ];

    public function ingredient(): BelongsTo
    {
        return $this->belongsTo(Ingredient::class, 'ingredient_id', 'id');
    }
}
