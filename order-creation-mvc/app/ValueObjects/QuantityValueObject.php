<?php

namespace App\ValueObjects;

class QuantityValueObject
{

    protected readonly  float $grams;

    public function __construct(float $value = 0,string $measure = 'g')
    {
        throw_if($value < 0,new \InvalidArgumentException('value can not be minus.'));

        $this->grams = match (strtolower($measure)) {
            'g'     => $value,
            'kg'    => $value * 1000,
            default => throw new \InvalidArgumentException('measure should equal "g" or "kg"')
        };
    }

    public final function toKilograms(): float
    {
        return $this->grams / 1000;
    }

    public final function toGrams(): float
    {
        return $this->grams;
    }

    public function subtract(float $value,string $measure = 'g'): self
    {
        $newQuantity = new self($value,$measure);

        $result =  $this->toGrams() - $newQuantity->toGrams();

        return new self($result,$measure);
    }

    public function add(float $value,string $measure = 'g'): self
    {
        $newQuantity = new self($value,$measure);

        $result =  $this->toGrams() + $newQuantity->toGrams();

        return new self($result,$measure);
    }
}
