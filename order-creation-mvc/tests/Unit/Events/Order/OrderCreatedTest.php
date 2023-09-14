<?php

use App\Events\Order\OrderCreatedEvent;
use App\Listeners\Order\IngredientLevelNotifier;
use App\Models\Order;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;

uses(Tests\TestCase::class, RefreshDatabase::class);

beforeEach(function () {
    Event::fake();
    Order::factory()->createOne();
});

describe('OrderCreatedEvent', function () {
    it('should dispatch orderCreated event when call event', function () {
        $fakeOrder = Order::query()->first();

        event(new OrderCreatedEvent($fakeOrder));

        event(new OrderCreatedEvent($fakeOrder));

        Event::assertDispatchedTimes(OrderCreatedEvent::class, 2);
    });

    it('should registered with its listeners', function () {
        event(new OrderCreatedEvent(Order::query()->first()));

        Event::assertListening(OrderCreatedEvent::class, IngredientLevelNotifier::class);
    });

    it('should have order property as Order object', function () {
        event($event = new OrderCreatedEvent(Order::query()->first()));

        expect($event->order)->toBeInstanceOf(Order::class);
    });
});
