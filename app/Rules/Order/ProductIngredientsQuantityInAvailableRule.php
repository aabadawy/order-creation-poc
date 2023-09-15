<?php

namespace App\Rules\Order;

use App\Models\Ingredient;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ProductIngredientsQuantityInAvailableRule implements ValidationRule
{
    public function __construct(
        public readonly string $productIdKeyName = 'id',
        public readonly string $quantityKeyName = 'quantity',
    ) {
    }

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (! is_array($value) && ! isset($value[$this->productIdKeyName]) && ! isset($value[$this->quantityKeyName])) {
            $fail("the :attribute must be array and have keys, $this->quantityKeyName, $this->productIdKeyName");
        }

        if (app()->runningUnitTests()) {
            //to avoid not supported right join in sqlite
            //todo handle condition when works with sqlite
            return;
        }

        $any_ingredient_quantity_not_available = Ingredient::query()
            ->whereAnyQuantityNotAvailableForProduct($value[$this->productIdKeyName], $value[$this->quantityKeyName])
            ->count();

        if ($any_ingredient_quantity_not_available) {
            $fail('the :attribute ingredients quantity will not match your requested quantity!');
        }
    }
}
