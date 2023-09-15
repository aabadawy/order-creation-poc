<?php

namespace Database\Factories;

use App\ValueObjects\QuantityValueObject;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Ingredient>
 */
class IngredientFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $init_quantity = $current_quantity = new QuantityValueObject($this->faker->randomFloat(2, 1000, 20000));

        return [
            'name' => $this->faker->name(),
            'init_quantity' => $init_quantity,
            'current_quantity' => $current_quantity,
        ];
    }

    public function quantityBelow(): Factory
    {
        return $this->state(function (array $attributes) {
            $init_quantity = new QuantityValueObject($this->faker->randomFloat(2, 10, 20), 'kg');

            return [
                'init_quantity' => $init_quantity,
                'current_quantity' => new QuantityValueObject(($init_quantity->toGrams() / 2) - 100, 'g'),
            ];
        });
    }
}
