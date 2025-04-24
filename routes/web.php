<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UrlScannerController;

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

Route::get('/', [UrlScannerController::class, 'index'])->name('scanner.index');
Route::post('/scan', [UrlScannerController::class, 'scan'])->name('scanner.scan');
Route::get('/history', [UrlScannerController::class, 'history'])->name('scanner.history');
