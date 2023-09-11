<?php

use App\Models\Ingredient;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses( RefreshDatabase::class);

beforeEach(function () {
    User::factory()->createOne(['email' => 'client@gmail.com','name' => 'Foo Baa']);
});

describe('CreateOrderController',function () {

    test('should return Ok response with 200 status code when data is valid', function () {

        $authClient = User::query()->first();

        $products = Product::factory(3)->create();

        $response = $this->actingAs($authClient)->post('/api/orders',[
            'products'  => [
                [
                    'id'    => $products->first()->getKey(),
                    'quantity'  => 1
                ]
            ]
        ]);

        $response->assertOk();
    });


    test('should throw 422 status code when validation failed',function () {
        $authClient = User::query()->first();

        $response = $this->actingAs($authClient)->post('/api/orders',[

        ]);

        $response->assertStatus(422);

        $response->assertInvalid([
            'products'
        ]);

        $otherResponse = $this->actingAs($authClient)->post('api/orders',[
            'products'  => [
                [
                    'id'    => -1,
                    'quantity'  => 0
                ]
            ]
        ]);

        $otherResponse->assertStatus(422);

        $otherResponse->assertInvalid([
            'products.0.id',
            'products.0.quantity',
        ]);
    });

    test('should create order with products and ingredients attached to created order', function () {

        $authClient = User::query()->first();

        $products = Product::factory(3)
            ->hasAttached(
                Ingredient::factory(3)->create(['init_quantity'=> 1000, 'current_quantity' => 1000])
                ,['quantity' => rand(100,500)]
            )
            ->create();

        expect(Order::query()->get())->toBeEmpty();

        $response = $this->actingAs($authClient)->post('/api/orders',[
            'products'  => [
                [
                    'id'    => $products->first()->getKey(),
                    'quantity'  => 1
                ]
            ]
        ]);

        $response->assertOk();

        expect(Order::query()->get())->toHaveCount(1);
    });
});
