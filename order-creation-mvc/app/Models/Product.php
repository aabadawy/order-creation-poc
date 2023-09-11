<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

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
            ->withPivot(['quantity'])
            ->withTimestamps();
    }
}
