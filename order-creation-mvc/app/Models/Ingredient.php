<?php

namespace App\Models;

use App\Casts\Quantity;
use App\EloquentBuilder\IngredientQueryBuilder;
use App\ValueObjects\QuantityValueObject;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * @property QuantityValueObject $init_quantity
 * @property QuantityValueObject $current_quantity
 */
class Ingredient extends Model
{
    use HasFactory;

    const LOW_QUANTITY_PERCENTAGE = 0.5;

    protected $fillable = [
        'name',
        'current_quantity',
        'quantity_below_email_sent_at',
    ];

    protected $casts = [
        'current_quantity' => Quantity::class,
        'init_quantity' => Quantity::class,
    ];

    public function products(): BelongsToMany
    {
        return $this
            ->belongsToMany(Product::class, 'product_ingredient')
            ->using(ProductIngredient::class)
            ->withPivot(['quantity'])
            ->withTimestamps();
    }

    public function subtractQuantity(float $quantity, string $measure = 'g'): void
    {
        $this->current_quantity = $this->current_quantity->subtract($quantity, $measure);
    }

    public function newEloquentBuilder($query): IngredientQueryBuilder
    {
        return new IngredientQueryBuilder($query);
    }
}
