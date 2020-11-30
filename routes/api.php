<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('/roman-numerals/latest', 'RomanNumeralController@latest');
Route::get('/roman-numerals/popular', 'RomanNumeralController@popular');
Route::get('/roman-numerals/{integer}', 'RomanNumeralController@show');
