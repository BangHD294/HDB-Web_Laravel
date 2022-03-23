<?php

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
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

//admin______________________________________________
//Route::prefix(['prefix' => 'admin','middleware' => ['auth', 'admin']])->group(function () {
//    Route::prefix('categories')->group(function () {
//        Route::get('/', [
//            'as' => 'categories.index',
//            'uses' => 'CategoryController@index',
//            'middleware' => 'can:category-list'
//
//        ]);
//    });
//});
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/dashboard', [
        'as' => 'dashboard',
        'uses' => [DashboardController::class, 'login'],
    ]);
});


Route::middleware(['auth', 'user'])->group(function () {
    Route::get('/dashboard', [
        'as' => 'dashboard',
        'uses' => [DashboardController::class, 'login'],
    ]);
});



//Route::group(['prefix' => 'admin', 'as' => 'admin.', 'namespace' => 'Admin', 'middleware' => ['auth', 'admin']], function () {
//    Route::get('dashboard', 'DashboardController@index')->name('dashboard');
//});
//
////user_______________________________________________
//Route::group(['prefix' => 'user', 'as' => 'user.', 'namespace' => 'User', 'middleware' => ['auth', 'user']], function () {
//    Route::get('dashboard', 'DashboardController@index')->name('dashboard');
//});
