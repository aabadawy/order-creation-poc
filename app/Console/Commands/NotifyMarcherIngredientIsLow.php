<?php

namespace App\Console\Commands;

use App\Commands\Ingredient\SendIngredientQuantityBelowEmailCommand;
use App\Models\Ingredient;
use Illuminate\Console\Command;

class NotifyMarcherIngredientIsLow extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:notify-marcher-ingredient-is-low';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle(SendIngredientQuantityBelowEmailCommand $sendIngredientQuantityBelowEmailCommand)
    {
        Ingredient::query()
            ->whereShouldSendQuantityBelowEmail()
            ->lazyById(100)
            ->each(function (Ingredient $ingredient) use ($sendIngredientQuantityBelowEmailCommand) {
                try {
                    $sendIngredientQuantityBelowEmailCommand->execute($ingredient);
                } catch (\Exception $exception) {
                    //continue
                }
            });
    }
}
