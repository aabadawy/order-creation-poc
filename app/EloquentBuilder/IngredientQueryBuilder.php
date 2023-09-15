<?php

namespace App\EloquentBuilder;

use App\Models\Ingredient;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class IngredientQueryBuilder extends Builder
{
    public function whereBelowTheAllowedPercentage(): self
    {
        return $this->whereColumn(
            'current_quantity',
            '<',
            DB::raw('init_quantity * '.Ingredient::LOW_QUANTITY_PERCENTAGE)
        );
    }

    public function whereQuantityBelowEmailSent(bool $sent = true): self
    {
        return $this->whereNull(
            'quantity_below_email_sent_at',
            'and',
            ! $sent
        );
    }

    public function whereShouldSendQuantityBelowEmail(): self
    {
        return $this
            ->whereBelowTheAllowedPercentage()
            ->whereQuantityBelowEmailSent();
    }

    public function whereAnyQuantityNotAvailableForProduct(int $product_id, int $product_quantity): self
    {
        return $this->rightJoin('product_ingredient','ingredients.id','product_ingredient.ingredient_id')
            ->select(DB::raw("(ingredients.current_quantity - product_ingredient.quantity * $product_quantity) > 0 as ingredient_quantity_available_in_stock"))
            ->where(DB::raw('product_ingredient.product_id'),$product_id)
            ->having('ingredient_quantity_available_in_stock',0)
            ->groupBy('ingredient_quantity_available_in_stock');
    }
}
