<?php

use App\Console\Commands\NotifyMarcherIngredientIsLow;
use App\Mail\IngredientQuantityBelow;
use App\Models\Ingredient;
use App\ValueObjects\QuantityValueObject;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

it('should call send ingredient below mail', function () {
    Mail::fake();

    $ingredient = Ingredient::factory()->createOne([
        'init_quantity' => new QuantityValueObject(10,'kg'),
        'current_quantity' => new QuantityValueObject(4,'kg'),
    ]);

    $this->artisan('app:notify-marcher-ingredient-is-low');

    expect($ingredient->refresh()->quantity_below_email_sent_at)->not->toBeNull();

    Mail::assertSent(IngredientQuantityBelow::class);
});
