<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ControlController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Endpoint para lanzar la generación de 500k registros
Route::get('/generate-large-data', [ControlController::class, 'generateLargeData']);

// Endpoints públicos para controles (GET solamente) protegidos por rate-limit 'rpm'
Route::get('/controls', [ControlController::class, 'index'])->middleware('rpm');
Route::get('/controls/{id}', [ControlController::class, 'show'])->middleware('rpm');
