<?php

namespace App\ValueObjects;

class QuantityValueObject
{
    protected readonly float $grams;

    public function __construct(float $value = 0, string $measure = 'g')
    {
        throw_if($value < 0, new \InvalidArgumentException('value can not be minus.'));

        $this->grams = match (strtolower($measure)) {
            'g' => $value,
            'kg' => $value * 1000,
            default => throw new \InvalidArgumentException('measure should equal "g" or "kg"')
        };
    }

    final public function toKilograms(): float
    {
        return $this->grams / 1000;
    }

    final public function toGrams(): float
    {
        return $this->grams;
    }

    public function subtract(float $value, string $measure = 'g'): self
    {
        $subtractedQuantity = new self($value, $measure);

        $result = $this->toGrams() - $subtractedQuantity->toGrams();

        return new self($result, $measure);
    }
}
