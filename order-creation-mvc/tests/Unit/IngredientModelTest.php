<?php

use App\Models\Ingredient;
use App\ValueObjects\QuantityValueObject;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(Tests\TestCase::class, RefreshDatabase::class);

describe('IngredientModelTest', function () {
    it('should have [name,init_quantity,current_quantity] in the ingredient attributes', function () {

        $ingredient = Ingredient::factory()->createOne([
            'name' => 'Onion',
            'init_quantity' => new QuantityValueObject(20000),
            'current_quantity' => new QuantityValueObject(1000),
        ]);

        expect($ingredient->toArray())->toHaveKeys(['name', 'init_quantity', 'current_quantity']);

        expect($ingredient->name)->toEqual('Onion');
        expect($ingredient->init_quantity->toGrams())->toEqual(20000);
        expect($ingredient->current_quantity->toGrams())->toEqual(1000);
    });

    it('should subtract quantity without saving in db', function () {

        $ingredient = Ingredient::factory()->createOne([
            'name' => 'Onion',
            'init_quantity' => new QuantityValueObject(2, 'kg'),
            'current_quantity' => new QuantityValueObject(1, 'kg'),
        ]);

        $ingredient->subtractQuantity(100);

        expect($ingredient->current_quantity->toGrams())->toEqual(900);

        expect($ingredient->refresh()->current_quantity->toGrams())->toEqual(1000);
    });
});
