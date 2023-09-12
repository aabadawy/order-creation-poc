<?php

namespace App\Http\Resources\Order;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => (int) $this->getKey(),
            'products' => OrderProductsResource::collection($this->whenLoaded('products')),
            'ingredients' => OrderProductIngredientResource::collection($this->whenLoaded('ingredients')),
        ];
    }
}
