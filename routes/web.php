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
    Route::get('/profile', [App\Http\Controllers\Admin\DashboardController::class, 'showProfile'])->name('profile');
    Route::put('/profile', [App\Http\Controllers\Admin\DashboardController::class, 'updateProfile'])->name('profile.update');
    Route::put('/profile/password', [App\Http\Controllers\Admin\DashboardController::class, 'changePassword'])->name('profile.password');
    Route::resource('users', UserController::class)->except(['create', 'show', 'edit', 'store']);
    Route::resource('category', \App\Http\Controllers\Admin\CategoryController::class)->except(['create', 'show', 'edit']);
    Route::resource('post', \App\Http\Controllers\Admin\PostController::class);
//    ->except(['create', 'show', 'edit']);
//    Route::get('profile', 'DashboardController@showProfile')->name('profile');
//    Route::put('profile', 'DashboardController@updateProfile')->name('profile.update');
//    Route::put('profile/password', 'DashboardController@changePassword')->name('profile.password');
});

//route user_______________________________________________
Route::group(['prefix' => 'user', 'as' => 'user.', 'middleware' => ['auth', 'user']], function () {

    Route::get('/dashboard', [App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');

});


