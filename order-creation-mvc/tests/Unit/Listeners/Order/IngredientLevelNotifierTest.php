<?php

use App\Commands\Ingredient\SendIngredientQuantityBelowEmailCommand;
use App\Events\Order\OrderCreatedEvent;
use App\Listeners\Order\IngredientLevelNotifier;
use App\Mail\IngredientQuantityBelow;
use App\Models\Ingredient;
use App\Models\Order;
use App\Models\Product;
use App\ValueObjects\QuantityValueObject;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Mail;

uses(Tests\TestCase::class, RefreshDatabase::class);

describe('IngredientLevelNotifier', function () {

    it('should be listened when orderCreated event dispatched', function () {
        Event::fake();

        event(
            new OrderCreatedEvent(
                Order::factory()->createOne()
            )
        );

        Event::assertListening(OrderCreatedEvent::class, IngredientLevelNotifier::class);
    });

    test('shouldQueue method should only return true when any of the order ingredient quantity belows', function () {
        $product = Product::factory()->create();

        Ingredient::factory()
            ->hasAttached($product, ['quantity' => new QuantityValueObject(100)], 'products')
            ->createOne([
                'init_quantity' => new QuantityValueObject(1000),
                'current_quantity' => new QuantityValueObject(580),
            ]);

        $ingredient = Ingredient::query()->first();

        $order = Order::factory()
            ->hasAttached($product, ['quantity' => 1])
            ->hasAttached($ingredient, ['quantity' => new QuantityValueObject(100)])
            ->create();

        expect(app(IngredientLevelNotifier::class)->shouldQueue(new OrderCreatedEvent($order)))->toBeFalse();

        $ingredient->subtractQuantity(100);

        $ingredient->save();

        expect(app(IngredientLevelNotifier::class)->shouldQueue(new OrderCreatedEvent($order->refresh())))->toBeTrue();
    });

    it('should send email to marcher and update the ingredient email with current date', function () {
        Event::fake();

        Mail::fake();

        $product = Product::factory()->create();

        Ingredient::factory(2)
            ->hasAttached($product, ['quantity' => new QuantityValueObject(100)], 'products')
            ->create([
                'init_quantity' => new QuantityValueObject(1000),
                'current_quantity' => new QuantityValueObject(480),
                'quantity_below_email_sent_at' => null
            ]);


        expect(Ingredient::query()->first()->quantity_below_email_sent_at)->toBeNull();

        expect(Ingredient::query()->latest()->first()->quantity_below_email_sent_at)->toBeNull();

        $order = Order::factory()
            ->hasAttached($product, ['quantity' => 1])
            ->hasAttached($product->ingredients, ['quantity' => new QuantityValueObject(100)])
            ->create();

        $ingredientsQuantityBelowCount = $order->ingredients()->whereShouldSendQuantityBelowEmail()->count();

        app(IngredientLevelNotifier::class)->handle(new OrderCreatedEvent($order));

        Mail::assertSent(IngredientQuantityBelow::class);

        Mail::assertSentCount($ingredientsQuantityBelowCount);

        expect($order->ingredients->first()->quantity_below_email_sent_at)->not->toBeNull();

        expect($order->ingredients->last()->quantity_below_email_sent_at)->not->toBeNull();
    });
});
