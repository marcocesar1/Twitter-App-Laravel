<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\FileController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Models\File;
use App\Models\Post;
use App\Models\User;
use Database\Factories\FileFactory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('auth/login', [AuthController::class, 'login'])->name('login');
Route::post('auth/register', [AuthController::class, 'register'])->name('register');
Route::post('auth/change-password', [AuthController::class, 'changePassword'])->middleware('auth:sanctum')->name('change-password');

Route::apiResource('posts', PostController::class)
        ->middleware('auth:sanctum')
        ->name('store', 'store_post')
        ->name('show', 'show_post')
        ->name('update', 'update_post')
        ->name('destroy', 'delete_post');

Route::apiResource('files', FileController::class)->middleware('auth:sanctum');

Route::get('profile/show/{user}', [ProfileController::class, 'me'])->middleware('auth:sanctum');
Route::get('profile/posts/{user}', [ProfileController::class, 'posts'])->middleware('auth:sanctum');
Route::apiResource('profile', ProfileController::class)->middleware('auth:sanctum');

Route::apiResource('comments', CommentController::class)->middleware('auth:sanctum');


Route::get('users/follow/{user}', [UserController::class, 'follow'])->middleware('auth:sanctum');
Route::get('users/unfollow/{user}', [UserController::class, 'unfollow'])->middleware('auth:sanctum');

Route::get('marco', function(){
    return User::all();
});

Route::get('marco/token', function(){
    /* User::factory(10)->create();

    User::all()->each(function($user){
        Post::factory(2)->make()->each(function($post) use ($user) {
            $files = File::factory(3)->create();

            
            $user->posts()->save($post);
            
            $post->files()->attach($files->pluck('id'));
        });
    }); */

    /* File::factory(5)->create();
    Post::factory(30)->create();
    User::factory()->create(); */
    $user = User::first();

    $token = $user->createToken('api');
 
    return ['token' => $token->plainTextToken, 'user' => $user];
});

Route::get('/public', function (Request $request) {

    $request->validate([
        'name' => 'required',
        'email' => 'required|email',
    ]);

    return 'public';
});

Route::get('/user', function (Request $request) {

    return User::with('profileImg')->find('6');
});

Route::get('/private', function () {
    return 'private';
})->middleware('auth:sanctum');

Route::get('factory', function(){
    //return File::factory()->image(100)->create();

    /* $user = User::factory()->create();
    return $user->load('profileImg'); */

    return Post::factory()->withUser()->create();
});

Route::get('followers', function(){
    $user = User::find(1);
    return $user->following()->get()->pluck('id');

    return User::with('following')->find(1);
});
