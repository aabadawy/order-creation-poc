<?php

namespace Database\Seeders;

use App\Models\Ingredient;
use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;

class InitProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $product = (new Product([
            'name'  => 'Burger'
        ]));

        $product->saveQuietly();

        $ingredients = Ingredient::query()
            ->whereIn('name',['Beef','Cheese','Onion'])
            ->limit(3)
            ->get();

        if ($ingredients->count() < 3) {
            throw new \Exception('please ensure to seed the init ingredients first!, using the command php artisan:seed InitIngredientSeeder');
        }

        $product_ingredients  = $ingredients->map(function(Ingredient $ingredient) {
            $product_ingredient_quantity = match (strtolower($ingredient->name)) {
                'beef'  => 150,
                'cheese' => 30,
                'onion' => 20,
            };

            return [
                'ingredient_id'    => $ingredient->getKey(),
                'quantity'         => $product_ingredient_quantity
            ];

        })
            ->keyBy('ingredient_id')
            ->toArray();

        foreach ($product_ingredients as $product_ingredient) {
            unset($product_ingredient['ingredient_id']);
        }

        $product->ingredients()->sync($product_ingredients);
    }
}
