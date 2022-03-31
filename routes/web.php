<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\UserController;

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

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

//route admin______________________________________________
Route::group(['prefix' => 'admin', 'as' => 'admin.',  'middleware' => ['auth', 'admin']], function () {

    Route::get('/dashboard', [App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');
    Route::resource('user', UserController::class)->except([
        'create', 'store', 'update', 'destroy'
    ]);
});

//route user_______________________________________________
Route::group(['prefix' => 'user', 'as' => 'user.', 'middleware' => ['auth', 'user']], function () {

    Route::get('/dashboard', [App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');

});


