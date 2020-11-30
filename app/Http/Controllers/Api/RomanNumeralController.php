<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\RomanNumeral;
use App\Services\IntegerConverterInterface;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

/**
 * Class RomanNumeralConverterController
 * @package App\Http\Controllers
 */
class RomanNumeralController extends Controller
{
    /**
     * @param   $integer
     * @return \App\Http\Resources\RomanNumeral
     */
    public function show($integer): \App\Http\Resources\RomanNumeral
    {
        Validator::validate(compact('integer'), [
            'integer' => [
                'required',
                'integer',
                'between:1,3999'
            ]
        ]);

        $numeral = app(IntegerConverterInterface::class)->convertInteger($integer);

        return new \App\Http\Resources\RomanNumeral(RomanNumeral::query()->create(compact('numeral', 'integer')));
    }

    /**
     * @return AnonymousResourceCollection
     */
    public function latest(): AnonymousResourceCollection
    {
        $perPage = request('perPage', 15);

        return \App\Http\Resources\RomanNumeral::collection(RomanNumeral::query()->latest()->paginate($perPage > 100 ? 100 : $perPage));
    }

    /**
     *
     */
    public function popular()
    {
        $rows = RomanNumeral::query()->select('integer', DB::raw('count(*) as count'))->groupBy('integer')->orderByDesc('count')->latest()->take(10)->get();

        return response()->json(['data' => $rows->map(fn($row) => ['integer' => (int)$row->integer, 'count' => (int)$row->count])]);

    }
}
