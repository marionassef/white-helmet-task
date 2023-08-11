<?php

use App\Http\Controllers\API\AuthorApiController;
use App\Http\Controllers\API\CommentApiController;
use App\Http\Controllers\API\PostApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('v1/authors', [PostApiController::class, 'store'])->name('post.store');
Route::post('v1/authors/login', [AuthorApiController::class, 'login'])->name('login');

Route::middleware('auth:api')->group(function () {

#### post Routes
    Route::prefix('v1/posts')->group(function () {
        Route::get('', [PostApiController::class, 'list'])->name('post.list');
        Route::get('{id}', [PostApiController::class, 'details'])->name('post.details');
        Route::put('{id}', [PostApiController::class, 'update'])->name('post.update');
        Route::delete('{id}', [PostApiController::class, 'delete'])->name('post.delete');
    });

#### author Routes
    Route::prefix('v1/authors')->group(function () {
        Route::get('', [AuthorApiController::class, 'list'])->name('author.list');
        Route::get('{id}', [AuthorApiController::class, 'details'])->name('author.details');
        Route::put('{id}', [AuthorApiController::class, 'update'])->name('author.update');
        Route::delete('{id}', [AuthorApiController::class, 'delete'])->name('author.delete');
    });

#### comment Routes
    Route::prefix('v1/posts/{post_id}/comments')->group(function () {
        Route::get('', [CommentApiController::class, 'list'])->name('comment.list');
        Route::post('', [CommentApiController::class, 'store'])->name('comment.store');
        Route::get('{id}', [CommentApiController::class, 'details'])->name('comment.details');
        Route::put('{id}', [CommentApiController::class, 'update'])->name('comment.update');
        Route::delete('{id}', [CommentApiController::class, 'delete'])->name('comment.delete');
    });
});
