<?php

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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('/2fa', [App\Http\Controllers\Auth\TwoFactorController::class, 'show'])->name('2fa');
Route::post('/2fa', [App\Http\Controllers\Auth\TwoFactorController::class, 'verify'])->name('2fa.verify');
