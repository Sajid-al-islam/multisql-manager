<?php

use App\Http\Controllers\QueryController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/', [QueryController::class, 'showForm'])->name('query-form');
Route::get('/convert', [QueryController::class, 'convertQueryForm'])->name('query-convert');
Route::post('/run-query', [QueryController::class, 'runQuery'])->name('run-query');
Route::post('/convert-query', [QueryController::class, 'convertQuery'])->name('convert-query');
