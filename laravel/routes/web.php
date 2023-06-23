<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
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
Route::middleware('LastOnlineAt')->group(function (){

    Route::get('register', [UserController::class, 'register'])->name('register');
    Route::post('register', [UserController::class, 'registerPost']);

    Route::get('login', [UserController::class, 'login'])->name('login');
    Route::post('login', [UserController::class, 'loginPost']);

    Route::get('/', [UserController::class, 'main'])->name('main');

    Route::group(['prefix' => '/profile', 'as' => 'profile.'], function (){
        Route::get('index', [UserController::class, 'index'])->name('index');
        Route::any('search', [UserController::class, 'search'])->name('search');
        Route::get('show/{user}', [UserController::class, 'show'])->name('show');
    });

    Route::group(['prefix' => '/post', 'as' => 'post.'], function (){
        Route::get('index', [\App\Http\Controllers\PostController::class, 'index'])->name('index');
        Route::get('show/{post}', [\App\Http\Controllers\PostController::class, 'show'])->name('show');
        Route::post('ordering', [\App\Http\Controllers\PostController::class, 'ordering'])->name('ordering');
        Route::get('filtration', [\App\Http\Controllers\PostController::class, 'filtration'])->name('filtration');
        Route::get('filtration/{filtration?}', [\App\Http\Controllers\PostController::class, 'filtration'])->name('filtration');

        Route::group(['prefix' => '/comment', 'as' => 'comment.'], function (){
            Route::get('filtration/{post}', [\App\Http\Controllers\CommentController::class, 'filtration'])->name('filtration');
        });
    });

    Route::middleware('auth')->group(function (){
        Route::get('logout', [UserController::class, 'logout'])->name('logout');

        Route::group(['prefix' => '/like', 'as' => 'like.'], function (){
            Route::post('check/{post}', [LikeController::class, 'check'])->name('check');
            Route::post('store/{post}', [LikeController::class, 'store'])->name('store');
        });

        Route::group(['prefix' => '/favorite', 'as' => 'favorite.'], function (){
            Route::post('check/{post}', [FavoriteController::class, 'check'])->name('check');
            Route::post('store/{post}', [FavoriteController::class, 'store'])->name('store');
        });

        Route::group(['prefix' => '/favorite', 'as' => 'favorite.'], function (){
            Route::get('store', [FavoriteController::class, 'store'])->name('store');
            Route::get('destroy', [FavoriteController::class, 'destroy'])->name('destroy');
        });

        Route::group(['prefix' => '/profile', 'as' => 'profile.'], function (){
            Route::middleware('isTrueUser')->group(function (){
                Route::get('update/{user}', [UserController::class, 'update'])->name('updateAccount');
                Route::put('updateAvatar/{user}', [UserController::class, 'updateAvatar'])->name('updateAccountAvatar');
                Route::put('updateAccountMain/{user}', [UserController::class, 'updateAccountMain'])->name('updateAccountMain');
                Route::put('updateAccountPassword/{user}', [UserController::class, 'updateAccountPassword'])->name('updateAccountPassword');
            });
        });

        Route::group(['prefix' => '/post', 'as' => 'post.'], function (){
            Route::get('choiceCategory', [\App\Http\Controllers\PostController::class, 'choiceCategory'])->name('choiceCategory');
            Route::get('create/{category}', [\App\Http\Controllers\PostController::class, 'create'])->name('create');
            Route::get('edit/{post}', [\App\Http\Controllers\PostController::class, 'edit'])->name('edit');
            Route::put('update/{post}', [\App\Http\Controllers\PostController::class, 'update'])->name('update');
            Route::post('store/{category}', [\App\Http\Controllers\PostController::class, 'store'])->name('store');
            Route::delete('destroy/{post}', [\App\Http\Controllers\PostController::class, 'destroy'])->name('destroy');

            Route::group(['prefix' => '/comment', 'as' => 'comment.'], function (){
                Route::post('store/{post}', [\App\Http\Controllers\CommentController::class, 'store'])->name('store');
                Route::post('update/{comment}/{post}', [\App\Http\Controllers\CommentController::class, 'update'])->name('update');
                Route::delete('destroy/{comment}/{post}', [\App\Http\Controllers\CommentController::class, 'destroy'])->name('destroy');
                Route::group(['prefix' => '/like', 'as' => 'like.'], function (){
                    Route::post('check/{comment}', [\App\Http\Controllers\LikesCommentController::class, 'check'])->name('check');
                    Route::post('store/{comment}', [\App\Http\Controllers\LikesCommentController::class, 'store'])->name('store');
                });
            });
        });

        Route::group(['prefix' => '/applications', 'as' => 'applications.'], function (){
            Route::get('index', [\App\Http\Controllers\StatusController::class, 'indexUser'])->name('index');
        });

        Route::middleware('isAdmin')->group(function (){
            Route::group(['prefix' => '/admin', 'as' => 'admin.'], function (){
                Route::resource('roles', RoleController::class);
                Route::resource('categories', CategoryController::class);
                Route::group(['prefix' => '/banneds', 'as' => 'banneds.'], function (){
                    Route::post('store/{user}', [\App\Http\Controllers\BannedController::class, 'store'])->name('store');
                    Route::delete('destroy/{banned}/{user}', [\App\Http\Controllers\BannedController::class, 'destroy'])->name('destroy');
                });
                Route::group(['prefix' => '/applications', 'as' => 'applications.'], function (){
                    Route::get('index', [\App\Http\Controllers\StatusController::class, 'index'])->name('index');
                    Route::delete('destroy/{post}', [\App\Http\Controllers\StatusController::class, 'destroy'])->name('destroy');
                    Route::post('store/{post}', [\App\Http\Controllers\StatusController::class, 'store'])->name('store');
                });
            });
        });

    });
});
