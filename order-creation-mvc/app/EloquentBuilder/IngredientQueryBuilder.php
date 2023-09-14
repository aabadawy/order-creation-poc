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
}
