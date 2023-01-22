<?php

use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\ResetPasswordController;
use Illuminate\Support\Facades\Route;

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
})->middleware('auth');
Route::post('/', [LoginController::class, 'logout'])->middleware('auth');

Route::get('/register', [RegisterController::class, 'index']);
Route::post("/register", [RegisterController::class, 'store']);

Route::get('/login', [LoginController::class, 'index'])->name('login');
Route::post('/login', [LoginController::class, 'authenticate']);
Route::get('/reload-captcha', [LoginController::class, 'reloadCaptcha']);

Route::get('/reset-password', [ResetPasswordController::class, 'index']);
Route::post('/reset-password', [ResetPasswordController::class, 'reset']);
