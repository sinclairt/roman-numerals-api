<?php

namespace Database\Factories;

use App\Models\RomanNumeral;
use App\Services\RomanNumeralConverter;
use Illuminate\Database\Eloquent\Factories\Factory;

class RomanNumeralFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = RomanNumeral::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $integer = rand(1, 3999);
        $numeral = (new RomanNumeralConverter())->convertInteger($integer);
        return compact('integer', 'numeral');
    }
}
