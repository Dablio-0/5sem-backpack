<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\BaseController;
use App\Http\Controllers\GeneticController;

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

Route::get('/', function () {
    return view('welcome');
});

Route::prefix('home')->name('home.')->group(function(){

    Route::get('/', [HomeController::class, 'index'])->name('index');

    Route::get('base', [BaseController::class, 'index'])->name('base.index');
    Route::post('base', [BaseController::class, 'solve'])->name('base.solve');

    Route::get('results', [BaseController::class, 'results'])->name('base.results');
    Route::post('improve', [BaseController::class, 'improve'])->name('base.improve');



    Route::get('genetic', [GeneticController::class, 'index'])->name('genetic.index');
    



});