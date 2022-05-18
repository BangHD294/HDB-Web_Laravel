<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\UserController;
use App\Models\Category;
use App\Models\Tag;
use App\Models\Post;
use Laravel\Socialite\Facades\Socialite;
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

Auth::routes(['verify'=>true]);
//
//social login
//Route::get('/auth/redirect', function () {
//    return Socialite::driver('google')->redirect();
//});
//
//Route::get('/auth/callback', function () {
//    $user = Socialite::driver('google')->user();
//    // $user->token
//});
Route::get('login/google', [App\Http\Controllers\Auth\LoginController::class, 'redirectToProvider']);
Route::get('login/google/callback', [App\Http\Controllers\Auth\LoginController::class, 'handleProviderCallback']);
//
//Route::get('/', 'HomeController@index')->name('home');
Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/posts', [App\Http\Controllers\HomeController::class, 'posts'])->name('posts');
Route::get('/post/{slug}', [App\Http\Controllers\HomeController::class, 'post'])->name('post');
Route::get('/categories', [App\Http\Controllers\HomeController::class, 'categories'])->name('categories');
Route::get('/category/{slug}', [App\Http\Controllers\HomeController::class, 'categoryPost'])->name('category.post');
Route::get('/search', [App\Http\Controllers\HomeController::class, 'search'])->name('search');
Route::get('/tag/{name}', [App\Http\Controllers\HomeController::class, 'tagPosts'])->name('tag.posts');
Route::post('/comment/{post}', [App\Http\Controllers\CommentController::class, 'store'])->name('comment.store')->middleware(['auth', 'verified']);
Route::post('/comment-reply/{comment}', [App\Http\Controllers\CommentReplyController::class, 'store'])->name('reply.store');
Route::post('/like-post/{post}', [App\Http\Controllers\HomeController::class, 'likePost'])->name('post.like')->middleware(['auth','verified']);

//test
//route admin______________________________________________
Route::group(['prefix' => 'admin', 'as' => 'admin.',  'middleware' => ['auth', 'admin']], function () {
    Route::get('/dashboard', [App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');
    Route::get('/profile', [App\Http\Controllers\Admin\DashboardController::class, 'showProfile'])->name('profile');
    Route::put('/profile', [App\Http\Controllers\Admin\DashboardController::class, 'updateProfile'])->name('profile.update');
    Route::put('/profile/password', [App\Http\Controllers\Admin\DashboardController::class, 'changePassword'])->name('profile.password');
    Route::resource('users', UserController::class)->except(['create', 'show', 'edit', 'store']);
    Route::resource('category', \App\Http\Controllers\Admin\CategoryController::class)->except(['create', 'show', 'edit']);
    Route::resource('post', \App\Http\Controllers\Admin\PostController::class);
    Route::get('/comments', [App\Http\Controllers\Admin\CommentController::class, 'index'])->name('comment.index');
    Route::delete('/comment/{id}', [App\Http\Controllers\Admin\CommentController::class, 'destroy'])->name('comment.destroy');
    Route::get('/reply-comments', [App\Http\Controllers\Admin\CommentReplyController::class, 'index'])->name('reply-comment.index');
    Route::delete('/reply-comment/{id}', [App\Http\Controllers\CommentReplyController::class, 'destroy'])->name('comment-reply.destroy');
    Route::get('/post-liked-users/{post}', [App\Http\Controllers\Admin\PostController::class, 'likedUsers'])->name('post.like.users');
});

//route user_______________________________________________
Route::group(['prefix' => 'user', 'as' => 'user.', 'middleware' => ['auth', 'user', 'verified']], function () {
//    test
    Route::get('dashboard', [App\Http\Controllers\User\DashboardController::class, 'likedPosts'])->name('dashboard');
    Route::get('profile', [App\Http\Controllers\User\DashboardController::class, 'showProfile'])->name('profile');
    Route::put('profile', [App\Http\Controllers\User\DashboardController::class, 'updateProfile'])->name('profile.update');
    Route::put('profile/password', [App\Http\Controllers\User\DashboardController::class, 'changePassword'])->name('profile.password');
    Route::get('comments', [App\Http\Controllers\User\CommentController::class, 'index'])->name('comment.index');
    Route::delete('/comment/{id}', [App\Http\Controllers\User\CommentController::class, 'destroy'])->name('comment.destroy');
    Route::get('/reply-comments', [App\Http\Controllers\User\CommentReplyController::class, 'index'])->name('reply-comment.index');
    Route::delete('/reply-comment/{id}', [App\Http\Controllers\User\CommentReplyController::class, 'destroy'])->name('reply-comment.destroy');
    Route::get('/user-liked-posts', [App\Http\Controllers\User\DashboardController::class, 'likedPosts'])->name('like.posts');

});

View::composer('layouts.frontend.partials.sidebar', function ($view) {
    $categories = Category::all()->take(10);
    $recentTags = Tag::all();
    $recentPosts = Post::latest()->take(3)->get();
    return $view->with('categories', $categories)->with('recentPosts', $recentPosts)->with('recentTags', $recentTags);
});
// Send Mail
Route::get('/send', function(){
    $post = Post::findOrFail(7);
    // Send Mail

//    Mail::to('user@user.com')
//        ->queue(new NewPost($post));
    Mail::to('user@user.com')->queue(new \App\Mail\NewPost($post));
    return (new App\Mail\NewPost($post))->render();
});




