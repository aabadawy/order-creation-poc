<?php

namespace App\Models;

use App\Casts\Quantity;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Collection;

/**
 * @property Collection<Ingredient> $ingredients
 */
class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name'
    ];

    public function ingredients():BelongsToMany
    {
        return $this->belongsToMany(Ingredient::class,
            'product_ingredient',
            'product_id',
            'ingredient_id'
        )
            ->using(ProductIngredient::class)
            ->withPivot(['quantity'])
            ->withTimestamps();
    }
}
