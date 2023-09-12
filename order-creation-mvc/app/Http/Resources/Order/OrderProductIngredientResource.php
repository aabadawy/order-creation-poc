<?php

namespace App\Http\Resources\Order;

use App\Models\Ingredient;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property Ingredient $resource
 */
class OrderProductIngredientResource extends JsonResource
{
    public function toArray(Request $request)
    {
        return [
            'id' => (int) $this->resource->getKey(),
            'name' => (string) $this->resource->name,
            'product_id' => (int) $this->resource->pivot->product_id,
            'quantity_stock_in_grams' => (float) $this->resource->current_quantity->toGrams(),
            'used_quantity' => (float) $this->resource->pivot->quantity->toGrams(),
        ];
    }
}
