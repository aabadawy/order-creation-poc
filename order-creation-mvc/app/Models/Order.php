<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id',
        'status',
    ];

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class,
            'order_products',
            'order_id',
            'product_id'
        )
            ->withTimestamps()
            ->withPivot(['quantity']);
    }

    public function ingredients(): BelongsToMany
    {
        return $this->belongsToMany(Ingredient::class,
            'order_products_ingredients',
            'order_id',
            'ingredient_id'
        )
            ->withTimestamps()
            ->withPivot(['quantity','product_id']);
    }
}
