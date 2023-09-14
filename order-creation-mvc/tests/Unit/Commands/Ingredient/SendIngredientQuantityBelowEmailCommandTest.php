<?php

use App\Commands\Ingredient\SendIngredientQuantityBelowEmailCommand;
use App\Mail\IngredientQuantityBelow;
use App\Models\Ingredient;
use App\ValueObjects\QuantityValueObject;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

beforeEach(function () {
    Mail::fake();
});

uses(TestCase::class, RefreshDatabase::class);

describe('SendIngredientQuantityBelowEmailCommand', function () {
    it('should send IngredientQuantityBelow email', function () {
        $sendIngredientQuantityBelowEmailCommand = app(SendIngredientQuantityBelowEmailCommand::class);

        $sendIngredientQuantityBelowEmailCommand->execute(
            Ingredient::factory()->quantityBelow()->createOne()
        );

        Mail::assertSent(IngredientQuantityBelow::class);
    });

    it('should update ingredient with email sent', function () {
        $sendIngredientQuantityBelowEmailCommand = app(SendIngredientQuantityBelowEmailCommand::class);

        $sendIngredientQuantityBelowEmailCommand->execute(
            Ingredient::factory()->quantityBelow()->createOne()
        );

        expect(Ingredient::query()->first()->quantity_below_email_sent_at)->not->toBeNull();
    });
});
