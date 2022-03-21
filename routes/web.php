<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\UsersController;
use App\Http\Controllers\Admin\EthnicitiesController;
use App\Http\Controllers\Admin\PreferencesController;
use App\Http\Controllers\Admin\ShowsController;
use App\Http\Controllers\Admin\CardsController;
use App\Http\Controllers\Admin\CategoryController;

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
    return view('auth/login');
})->name('admin');
Route::name('auth.')->group(function () {
    Route::post('attempt', [UsersController::class, 'login'])->name('attempt');
    Route::get('change-password', function () {
        return view('pages/change-password');
    })->name('change-password');
});
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('dashboard', [UsersController::class, 'dashboard'])->name('dashboard');
    Route::get('logout', [UsersController::class, 'logout'])->name('logout');
    Route::name('users.')->prefix('users')->group(function () {
        Route::get('list', function () {
            return view('users/list');
        })->name('list');
        Route::post('list', [UsersController::class, 'usersList'])->name('list');
    });
    Route::name('ethnicities.')->prefix('ethnicities')->group(function () {
        Route::get('list', function () {
            return view('ethnicities/list');
        })->name('list');
        Route::get('add', function () {
            return view('ethnicities/add');
        })->name('add');
        Route::post('list', [EthnicitiesController::class, 'ethnicitiesList'])->name('list');
        Route::post('add', [EthnicitiesController::class, 'addEthnicity'])->name('add');
        Route::post('delete', [EthnicitiesController::class, 'deleteEthnicity'])->name('delete');
    });
    Route::name('preferences.')->prefix('preferences')->group(function () {
        Route::get('list', function () {
            return view('preferences/list');
        })->name('list');
        Route::get('add', function () {
            return view('preferences/add');
        })->name('add');
        Route::post('list', [PreferencesController::class, 'preferencesList'])->name('list');
        Route::post('add', [PreferencesController::class, 'addPreference'])->name('add');
        Route::post('delete', [PreferencesController::class, 'deletePreference'])->name('delete');
    });
    Route::name('shows.')->prefix('shows')->group(function () {
        Route::get('add', function () {
            return view('shows/add');
        })->name('add');
        Route::get('list', [ShowsController::class, 'showsList'])->name('list');
        Route::post('add', [ShowsController::class, 'addShow'])->name('add');
        Route::get('delete/{id}', [ShowsController::class, 'deleteShow'])->name('delete');
        Route::get('edit/{id}', [ShowsController::class, 'editShow'])->name('edit');
        Route::post('update/{id}', [ShowsController::class, 'updateShow'])->name('update');
        Route::post('search', [ShowsController::class, 'showSearch'])->name('search');

    });
    Route::name('cards.')->prefix('cards')->group(function () {

        Route::get('add', function () {
            return view('cards/add');
        })->name('add');
        Route::get('list', [CardsController::class, 'cardsList'])->name('list');
        Route::post('add', [CardsController::class, 'addCard'])->name('add');
        Route::get('delete/{id}', [CardsController::class, 'deleteCard'])->name('delete');
        Route::get('edit/{id}', [CardsController::class, 'editCard'])->name('edit');
        Route::post('update/{id}', [CardsController::class, 'updateCard'])->name('update');
        Route::post('search', [CardsController::class, 'cardSearch'])->name('search');

    });

    Route::name('category.')->prefix('catgory')->group(function () {
        Route::resource('list',CategoryController::class);
        Route::get('inactive/{id}',[CategoryController::class,'inactive'])->name('inactive');
        Route::post('search', [CategoryController::class, 'categorySearch'])->name('search');

    });

});
