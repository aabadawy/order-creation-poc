<?php

use App\Mail\IngredientQuantityBelow;
use App\Models\Ingredient;
use App\ValueObjects\QuantityValueObject;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

describe('IngredientsQuantityIsBelow', function () {
   it('should define the sender and send to data', function () {

       $ingredient = Ingredient::factory()->createOne([
           'init_quantity'  => new QuantityValueObject(2,'kg'),
           'current_quantity'   => new QuantityValueObject(900,'g'),
       ]);

       $mailable = new IngredientQuantityBelow($ingredient);

       $mailable->assertFrom('app@foodics.com');

       $mailable->assertTo('marcher@example.com');

       $mailable->assertSeeInHtml($ingredient->name);
   });
});
