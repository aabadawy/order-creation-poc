<?php

use App\Commands\Order\CreateOrderCommand;
use App\DTOs\Order\OrderData;
use App\Models\Ingredient;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\ValueObjects\QuantityValueObject;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(Tests\TestCase::class, RefreshDatabase::class);

describe('CreateOrderCommand', function () {
    it('should return order object and products,ingredients relations are loaded', function () {

        Product::factory()->hasAttached(
            Ingredient::factory(3)->create(), ['quantity' => (new QuantityValueObject(100, 'g'))]
        )->createOne();

        $products = [
            [
                'id' => 1,
                'quantity' => 1,
            ],
        ];

        $createOrderCommand = app(CreateOrderCommand::class);

        $authClient = User::factory()->createOne();

        $result = $createOrderCommand->execute(OrderData::from([
            'client' => $authClient,
            'products' => $products,
        ]));

        expect($result)->toBeInstanceOf(Order::class);

        expect($result->relationLoaded('products'))->toBeTrue();

        expect($result->relationLoaded('ingredients'))->toBeTrue();
    });
});
