<?php

use Illuminate\Http\Request;
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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::apiResource('domains', '\App\Http\Controllers\Api\DomainController');
Route::apiResource('subdomains', '\App\Http\Controllers\Api\SubdomainController');
Route::apiResource('servers', '\App\Http\Controllers\Api\ServerController');
Route::apiResource('api-services', '\App\Http\Controllers\Api\ApiServiceController');
Route::apiResource('accounts', '\App\Http\Controllers\Api\AccountController');
Route::apiResource('logs', '\App\Http\Controllers\Api\LogController');
