<?php

namespace App\Commands\Ingredient;

use App\Mail\IngredientQuantityBelow;
use App\Models\Ingredient;
use Illuminate\Support\Facades\Mail;
use Mockery\Exception;

class SendIngredientQuantityBelowEmailCommand
{

    public function execute(Ingredient $ingredient): Ingredient
    {
        Mail::send(new IngredientQuantityBelow($ingredient));

        $ingredient->update([
            'quantity_below_email_sent_at' => now(),
        ]);

        return $ingredient->refresh();
    }
}
