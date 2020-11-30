<?php

namespace Tests\Feature;

use App\Models\RomanNumeral;
use App\Services\RomanNumeralConverter;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class RomanNumeralTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_it_shows_the_converted_integer()
    {
        $response = $this->getJson('/api/roman-numerals/1975');

        $numeral = RomanNumeral::query()->latest()->first();
        $response->assertStatus(201)
                 ->assertJsonFragment([
                     'id'         => (int)$numeral->id,
                     'numeral'    => 'MCMLXXV',
                     'integer'    => 1975,
                     'created_at' => $numeral->created_at->toString(),
                     'updated_at' => $numeral->updated_at->toString(),
                 ]);
    }

    public function test_it_fails_if_the_value_is_not_an_integer_between_1_and_3999()
    {
        $this->getJson('/api/roman-numerals/0')->assertStatus(422)->assertJson([
            'message' => 'The given data was invalid.',
            'errors'  => [
                'integer' => [
                    trans('validation.between.numeric', ['attribute' => 'integer', 'min' => '1', 'max' => '3999'])
                ]
            ]
        ]);
    }

    public function test_it_can_show_the_most_recent_conversions()
    {
        $count = 20;
        RomanNumeral::factory()->count($count)->create();
        $numerals = RomanNumeral::query()->latest()->take(15)->get();

        $response = $this->getJson('/api/roman-numerals/latest');

        $response->assertStatus(200);

        foreach ($numerals as $numeral) {
            $response->assertSee([
                'id'         => (int)$numeral->id,
                'numeral'    => $numeral->numeral,
                'integer'    => (int)$numeral->integer,
                'created_at' => $numeral->created_at->toString(),
                'updated_at' => $numeral->updated_at->toString(),
            ]);
        }
    }

    public function test_it_can_show_the_most_popular_conversions()
    {
        $converter = new RomanNumeralConverter;
        RomanNumeral::factory()->count(5)->create(['numeral' => $converter->convertInteger(2019), 'integer' => 2019]);
        RomanNumeral::factory()->count(8)->create(['numeral' => $converter->convertInteger(2020), 'integer' => 2020]);
        RomanNumeral::factory()->count(2)->create(['numeral' => $converter->convertInteger(3998), 'integer' => 3998]);
        RomanNumeral::factory()->count(2)->create(['numeral' => $converter->convertInteger(1967), 'integer' => 1967]);
        RomanNumeral::factory()->count(4)->create(['numeral' => $converter->convertInteger(340), 'integer' => 340]);
        RomanNumeral::factory()->count(4)->create(['numeral' => $converter->convertInteger(1111), 'integer' => 1111]);
        RomanNumeral::factory()->count(3)->create(['numeral' => $converter->convertInteger(3), 'integer' => 3]);
        RomanNumeral::factory()->count(10)->create(['numeral' => $converter->convertInteger(2017), 'integer' => 2017]);
        RomanNumeral::factory()->count(9)->create(['numeral' => $converter->convertInteger(286), 'integer' => 286]);
        RomanNumeral::factory()->create(['numeral' => $converter->convertInteger(1988), 'integer' => 1988]);

        $response = $this->getJson('/api/roman-numerals/popular');

        $response->assertStatus(200);

        $response->assertJson([
            'data' => [
                [
                    'integer' => 2017,
                    'count'   => 10
                ],
                [
                    'integer' => 286,
                    'count'   => 9
                ],
                [
                    'integer' => 2020,
                    'count'   => 8
                ],
                [
                    'integer' => 2019,
                    'count'   => 5
                ],
                [
                    'integer' => 340,
                    'count'   => 4
                ],
                [
                    'integer' => 1111,
                    'count'   => 4
                ],
                [
                    'integer' => 3,
                    'count'   => 3
                ],
                [
                    'integer' => 1967,
                    'count'   => 2
                ],
                [
                    'integer' => 3998,
                    'count'   => 2
                ],
                [
                    'integer' => 1988,
                    'count'   => 1
                ],
            ]
        ]);
    }
}
