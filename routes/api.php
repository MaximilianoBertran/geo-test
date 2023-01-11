<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\PlayerController;
use App\Http\Controllers\Api\TournamentController;
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


Route::post('user/register', [AuthController::class, 'register']);
Route::post('user/login', [AuthController::class, 'login']);

Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::get('user/user-profile', [AuthController::class, 'userProfile']);
    Route::post('user/logout', [AuthController::class, 'logout']);
    Route::prefix('tournament')->group(function () {
        Route::get('/', [TournamentController::class, 'index']);
        Route::get('gender/{gender_id}', [TournamentController::class, 'index']);
        Route::post('store', [TournamentController::class, 'store']);
        Route::get('show/{id}', [TournamentController::class, 'show']);
    });
    Route::prefix('player')->group(function () {
        Route::get('/', [PlayerController::class, 'index']);
        Route::get('show/{id}', [PlayerController::class, 'show']);
    });
});
