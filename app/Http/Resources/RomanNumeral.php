<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;

/**
 * @property int id
 * @property string numeral
 * @property int integer
 * @property Carbon created_at
 * @property Carbon updated_at
 */
class RomanNumeral extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id'         => (int)$this->id,
            'numeral'    => (string)$this->numeral,
            'integer'    => (int)$this->integer,
            'created_at' => $this->created_at->toString(),
            'updated_at' => $this->updated_at->toString()
        ];
    }
}
