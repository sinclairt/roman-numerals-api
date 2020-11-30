<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Conversion
 * @package App\Models
 */
class RomanNumeral extends Model
{
    use HasFactory;

    /**
     * @var string[]
     */
    protected $fillable = ['integer', 'numeral'];
}
