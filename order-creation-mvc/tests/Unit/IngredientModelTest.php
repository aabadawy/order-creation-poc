<?php

use Illuminate\Foundation\Testing\RefreshDatabase;

uses(Tests\TestCase::class, RefreshDatabase::class);

test('should have [name,init_quantity,current_quantity] in the ingredient attributes', function () {

    $ingredient = \App\Models\Ingredient::factory()->createOne([
        'name'  => 'Onion',
        'init_quantity' => 20000,
        'current_quantity' => 1000
    ]);

    expect($ingredient->toArray())->toHaveKeys(['name','init_quantity','current_quantity']);

    expect($ingredient->name)->toEqual('Onion');
    expect($ingredient->init_quantity)->toEqual(20000);
    expect($ingredient->current_quantity)->toEqual(1000);
});
