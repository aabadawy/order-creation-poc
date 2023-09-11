<?php

namespace Database\Seeders;

use App\Models\Ingredient;
use App\ValueObjects\QuantityValueObject;
use Illuminate\Database\Seeder;

class InitIngredientSeeder extends Seeder
{
    /**
     * Run the ingredients seeds to init the default value.
     */
    public function run(): void
    {
        $quantities = [
            'beef' => new QuantityValueObject(20, 'kg'),
            'cheese' => new QuantityValueObject(5, 'kg'),
            'onion' => new QuantityValueObject(1, 'kg'),
        ];

        $ingredients = [
            [
                'name' => 'Beef',
                'init_quantity' => $quantities['beef'],
                'current_quantity' => $quantities['beef'],
            ],
            [
                'name' => 'Cheese',
                'init_quantity' => $quantities['cheese'],
                'current_quantity' => $quantities['cheese'],
            ],
            [
                'name' => 'Onion',
                'init_quantity' => $quantities['onion'],
                'current_quantity' => $quantities['onion'],
            ],
        ];

        foreach ($ingredients as $ingredient) {
            Ingredient::query()->forceCreateQuietly($ingredient);
        }
    }
}
