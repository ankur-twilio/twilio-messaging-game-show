<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\WelcomeSEEventController;
use App\Http\Controllers\GameshowController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [WelcomeSEEventController::class, 'index']);
Route::get('/game/{game}', [WelcomeSEEventController::class, 'game']);
Route::get('/game/{game}/questions', [WelcomeSEEventController::class, 'questions']);
Route::post('/game/{game}/wipe', [WelcomeSEEventController::class, 'wipe']);
Route::get('/game/{game}/question/{question}', [WelcomeSEEventController::class, 'question']);

Route::prefix('twilio')->middleware(['twilio'])->group(function () {
    Route::get('/gameshow', [GameshowController::class, 'index']);
    Route::post('/gameshow', [GameshowController::class, 'index']);
});

Route::prefix('jobs')->group(function () {
    Route::queueMonitor();
});