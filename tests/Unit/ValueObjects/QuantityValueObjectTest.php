<?php

use App\ValueObjects\QuantityValueObject;

describe('QuantityValueObject', function () {
    it('should create quantityValueObject using grams', function () {
        $grams = 1000;

        $quantityValueObject = new QuantityValueObject($grams);

        expect($quantityValueObject->toGrams())->toEqual($grams);
    });

    it('should create quantityValueObject using kilograms', function () {
        $kiloGrams = 1;

        $quantityValueObject = new QuantityValueObject($kiloGrams, 'kg');

        expect($quantityValueObject->toKilograms())->toEqual($kiloGrams);
    });

    it('should throw exception when measure not supported', function () {
        $this->expectException(InvalidArgumentException::class);

        expect(new QuantityValueObject(0, 'foo'))->toThrow(InvalidArgumentException::class);
    });

    it('should throw exception when measure value less than 0', function () {
        $this->expectException(Exception::class);

        expect(new QuantityValueObject(-1))->toThrow(Exception::class);
    });

    it('should convert grams to kilograms and the reverse of it', function () {
        $gramsQuantityValueObject = new QuantityValueObject(1000);

        expect($gramsQuantityValueObject->toGrams())->toEqual(1000);

        expect($gramsQuantityValueObject->toKilograms())->toEqual(1);

        $kilogramsQuantityValueObject = new QuantityValueObject(1, 'kg');

        expect($kilogramsQuantityValueObject->toGrams())->toEqual(1000);

        expect($kilogramsQuantityValueObject->toKilograms())->toEqual(1);
    });
});
