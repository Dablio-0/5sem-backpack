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
    Route::post('improve/{initialSolution?}/
                        {generatedProblem?}/
                        {items?}/
                        {itemCount?}/
                        {maxCapacity?}/
                        {evaluation?}', [BaseController::class, 'improve'])->name('base.improve');
    Route::get('exportImprove', [BaseController::class, 'exportImprove'])->name('base.exportImprove');


    Route::get('genetic', [GeneticController::class, 'index'])->name('genetic.index');

});