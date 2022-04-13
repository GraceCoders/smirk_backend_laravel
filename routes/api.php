<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\v1\UsersController;
use App\Http\Controllers\Api\v1\EthnicitiesController;
use App\Http\Controllers\Api\v1\PreferencesController;
use App\Http\Controllers\Api\v1\CardsController;
use App\Http\Controllers\Api\v1\ShowsController;
use App\Http\Controllers\Api\v1\UserLikeController;
use App\Http\Controllers\Api\v1\ChatController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::prefix('/v1')->group(function () {
    Route::prefix('/auth')->group(function () {
        Route::post('/sign-up', [UsersController::class, 'signUp'])->name('sign-up');
        Route::post('/login', [UsersController::class, 'login'])->name('login');
        Route::post('/verify', [UsersController::class, 'verifyOTP'])->name('verify');
        Route::get('/getShow', [ShowsController::class, 'getShow']);
    });
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/upload-photo', [UsersController::class, 'uploadPhoto'])->name('upload-photo');
        Route::post('/update-image', [UsersController::class, 'updateImage'])->name('update-image');
        Route::post('/update-image', [UsersController::class, 'deleteImage'])->name('update-image');
        Route::post('/action-on-card', [CardsController::class, 'actionOnCard'])->name('action-on-card');
        Route::post('/user-detail', [UsersController::class, 'userDetail'])->name('user-detail');
        Route::post('/update-profile', [UsersController::class, 'updateProfile'])->name('update-profile');
        Route::get('/get-match', [CardsController::class, 'getMatch'])->name('get-match');
        Route::post('/user-like', [UserLikeController::class, 'likeUser'])->name('user-like');
        Route::post('/get-match-card', [CardsController::class, 'getMatchcard'])->name('get-match-card');

        Route::post('/user-block', [UserLikeController::class, 'block'])->name('user-block');
        Route::get('/user-block-list', [UserLikeController::class, 'blockUserList'])->name('user-block-list');
        Route::post('/user-report', [UserLikeController::class, 'report'])->name('user-report');

        Route::post('/update-images', [UsersController::class, 'updateImages'])->name('update-images');
        Route::get('/delete-images', [UsersController::class, 'deleteImage'])->name('delete-images');
        Route::post('/add-question', [UsersController::class, 'Question'])->name('add-question');

        //chat room api 
        Route::get('/chat-room', [ChatController::class, 'getRoom'])->name('chat-room');
        // notification 


    });
    Route::get('/get-notification', [ChatController::class, 'getNotification'])->name('get-notification');

    Route::get('/retrieve-list', [UsersController::class, 'retrieveList'])->name('retrieve-list');
    Route::get('/cards-list', [CardsController::class, 'cardsList'])->name('cards-list');
});
