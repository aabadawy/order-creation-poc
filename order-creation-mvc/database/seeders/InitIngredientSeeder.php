<?php

namespace Database\Seeders;

use App\Models\Ingredient;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class InitIngredientSeeder extends Seeder
{
    /**
     * Run the ingredients seeds to init the default value.
     */
    public function run(): void
    {
        $ingredients = [
            [
              'name'    => 'Beef',
              'init_quantity'    => 20000,
              'current_quantity'  => 20000,
            ],
            [
                'name'  => 'Cheese',
                'init_quantity'  => 5000,
                'current_quantity'  => 5000,
            ],
            [
                'name'  => 'Onion',
                'init_quantity'  => 1000,
                'current_quantity'  => 1000,
            ]
        ];

        foreach ($ingredients as $ingredient) {
            Ingredient::query()->forceCreateQuietly($ingredient);
        }
    }
}
