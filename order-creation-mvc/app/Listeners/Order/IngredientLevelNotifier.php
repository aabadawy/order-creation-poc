<?php

namespace App\Listeners\Order;

use App\Commands\Ingredient\SendIngredientQuantityBelowEmailCommand;
use App\Events\Order\OrderCreatedEvent;
use App\Models\Ingredient;
use Illuminate\Contracts\Queue\ShouldQueue;

class IngredientLevelNotifier implements ShouldQueue
{
    /**
     * Create the event listener.
     */
    public function __construct(
        protected SendIngredientQuantityBelowEmailCommand $sendIngredientQuantityBelowEmailCommand
    ) {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(OrderCreatedEvent $event): void
    {
        $event->order
            ->ingredients()
            ->whereShouldSendQuantityBelowEmail()
            ->get()->each(function (Ingredient $ingredient) {
                $this->sendIngredientQuantityBelowEmailCommand->execute($ingredient);
            });
    }

    public function shouldQueue(OrderCreatedEvent $event): bool
    {
        return $event->order
            ->ingredients()
            ->whereShouldSendQuantityBelowEmail()
            ->exists();
    }
}
