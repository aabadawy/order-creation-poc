<?php

namespace App\Models;

use App\Casts\Quantity;
use App\ValueObjects\QuantityValueObject;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

/**
 * @property QuantityValueObject $init_quantity
 * @property QuantityValueObject $current_quantity
 */
class Ingredient extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'current_quantity',
    ];

    protected $casts = [
        'current_quantity' => Quantity::class,
        'init_quantity' => Quantity::class,
    ];

    public function canSubtractQuantity(float $quantity, string $measure = 'g'): bool
    {
        try {
            return $this->current_quantity->subtract($quantity, $measure)->toGrams() > 0;
        } catch (\Exception $exception) {
            Log::info($exception->getMessage());

            return false;
        }
    }

    public function subtractQuantity(float $quantity, string $measure = 'g'): void
    {
        $this->current_quantity = $this->current_quantity->subtract($quantity, $measure);
    }

    public function addQuantity(float $quantity, string $measure = 'g'): void
    {
        $this->current_quantity = $this->current_quantity->add($quantity, $measure);
    }
}
