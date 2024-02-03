<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Admin\AuthController;
use App\Http\Controllers\Api\CategoriesController;

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
Route::group(['middleware'=>['api','checkpassword','changelang']],function (){

    Route::post('get_main_categories',[CategoriesController::class,'index']);
    Route::post('get_category-byId',[CategoriesController::class,'getCategoryById']);
    Route::post('change_category_status',[CategoriesController::class,'changeStatus']);



    Route::group(['prefix'=>'admin'],function(){
        Route::post('login',[AuthController::class,'login']);
        Route::post('logout',[AuthController::class,'logout']) -> middleware(['auth.guard:admin-api']);
    });

    Route::group(['prefix' => 'user','namespace'=>'User'],function (){
        Route::post('login',[App\Http\Controllers\Api\User\AuthController::class,'login']);
    });
    Route::group(['prefix' => 'user','middleware'=>'auth.guard:user-api'],function (){
        Route::post('logout',[App\Http\Controllers\Api\User\AuthController::class,'logout']);
    });

    Route::group(['prefix'=>'user','middleware'=>'auth.guard:user-api'],function(){
        Route::post('profile',function(){
            return Auth::user();
        });
    });


});

Route::group(['middleware'=>['api','checkpassword','changelang','checkAdminToken:admin-api']],function (){
    Route::get('offer',[CategoriesController::class,'index'] );
});

