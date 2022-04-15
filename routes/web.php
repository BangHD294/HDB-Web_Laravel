<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\UserController;
use App\Models\Category;
use App\Models\Tag;
use App\Models\Post;
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

//Route::get('/', function () {
//    return view('welcome');
//});

Auth::routes();
//Route::get('/', 'HomeController@index')->name('home');
Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/posts', [App\Http\Controllers\HomeController::class, 'posts'])->name('posts');
Route::get('/post/{slug}', [App\Http\Controllers\HomeController::class, 'post'])->name('post');
Route::get('/categories', [App\Http\Controllers\HomeController::class, 'categories'])->name('categories');
Route::get('/category/{slug}', [App\Http\Controllers\HomeController::class, 'categoryPost'])->name('category.post');
Route::get('/search', [App\Http\Controllers\HomeController::class, 'search'])->name('search');
Route::get('/tag/{name}', [App\Http\Controllers\HomeController::class, 'tagPosts'])->name('tag.posts');
Route::get('/comment/{post}', [App\Http\Controllers\HomeController::class, 'store'])->name('comment.store');

//
////Route::post('/comment/{post}', 'CommentController@store')->name('comment.store')->middleware(['auth', 'verified']);
//Route::post('/comment/{post}', 'CommentController@store')->name('comment.store')->middleware(['auth']);
////Route::post('/comment-reply/{comment}', 'CommentReplyController@store')->name('reply.store')->middleware(['auth', 'verified']);
//Route::post('/comment-reply/{comment}', 'CommentReplyController@store')->name('reply.store')->middleware(['auth']);
////Route::post('/like-post/{post}', 'HomeController@likePost')->name('post.like')->middleware(['auth', 'verified']);
//Route::post('/like-post/{post}', 'HomeController@likePost')->name('post.like')->middleware(['auth']);
//

//route admin______________________________________________
Route::group(['prefix' => 'admin', 'as' => 'admin.',  'middleware' => ['auth', 'admin']], function () {

    Route::get('/dashboard', [App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');
    Route::get('/profile', [App\Http\Controllers\Admin\DashboardController::class, 'showProfile'])->name('profile');
    Route::put('/profile', [App\Http\Controllers\Admin\DashboardController::class, 'updateProfile'])->name('profile.update');
    Route::put('/profile/password', [App\Http\Controllers\Admin\DashboardController::class, 'changePassword'])->name('profile.password');
    Route::resource('users', UserController::class)->except(['create', 'show', 'edit', 'store']);
    Route::resource('category', \App\Http\Controllers\Admin\CategoryController::class)->except(['create', 'show', 'edit']);
    Route::resource('post', \App\Http\Controllers\Admin\PostController::class);

});

//route user_______________________________________________
Route::group(['prefix' => 'user', 'as' => 'user.', 'middleware' => ['auth', 'user']], function () {

    Route::get('/dashboard', [App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');

});

View::composer('layouts.frontend.partials.sidebar', function ($view) {
    $categories = Category::all()->take(10);
    $recentTags = Tag::all();
    $recentPosts = Post::latest()->take(3)->get();
    return $view->with('categories', $categories)->with('recentPosts', $recentPosts)->with('recentTags', $recentTags);
});



