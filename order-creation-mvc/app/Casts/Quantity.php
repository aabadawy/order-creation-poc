<?php

namespace App\Casts;

use App\ValueObjects\QuantityValueObject;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;

class Quantity implements CastsAttributes
{
    /**
     * Cast the given value.
     *
     * @param  array<string, mixed>  $attributes
     */
    public function get(Model $model, string $key, mixed $value, array $attributes): mixed
    {
        return new QuantityValueObject($attributes[$key]);
    }

    /**
     * Prepare the given value for storage.
     *
     * @param  array<string, mixed>  $attributes
     */
    public function set(Model $model, string $key, mixed $value, array $attributes): mixed
    {
        if(! $value instanceof  QuantityValueObject) {
            throw new \InvalidArgumentException('The given value is not an QuantityValueObject instance.');
        }

        if ($value->toGrams() < 0) {
            throw new \RuntimeException("ingredient with id:{$model->getKey()} quantity out of stock.");
        }

        return $value->toGrams();
    }
}
