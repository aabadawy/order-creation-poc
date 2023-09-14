<?php

use App\Casts\Quantity;
use App\Models\Ingredient;
use App\ValueObjects\QuantityValueObject;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

describe('QuantityCanst',function () {
    test('setter method should only accept QuantityValueObject', function () {
        (new Quantity())
            ->set(
                new Ingredient(),
                'current_quantity',
                123.4,
                [
                    'current_quantity',
                    'init_quantity'
                ]
            );
    })->throws(InvalidArgumentException::class,'The given value is not an QuantityValueObject instance.');

    it('setter method should return value in grams as float',function () {
        $response = (new Quantity())
            ->set(
                new Ingredient(),
                'current_quantity',
                new QuantityValueObject(1,'kg'),
                [
                    'current_quantity',
                    'init_quantity'
                ]
            );

        expect($response)->toBeFloat()->toEqual(1000);
    });

    test('getter method should return QuantityValueObject',function () {
       Ingredient::factory()->createOne([
           'init_quantity'   => new QuantityValueObject(1,'kg'),
           'current_quantity'   => new QuantityValueObject(1,'kg'),
       ]);


       $savedIngredient = Ingredient::query()->latest()->first();

       expect($savedIngredient->current_quantity)->toBeInstanceOf(QuantityValueObject::class);

       expect($savedIngredient->current_quantity->toGrams())->toEqual(1000);
    });
});
