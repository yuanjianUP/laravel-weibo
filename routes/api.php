<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\VerificationCodesController;
use App\Http\Controllers\Api\UsersController;
use App\Http\Controllers\Api\CaptchasController;
use App\Http\Controllers\Api\AuthorizationsController;
use App\Http\Controllers\Api\ImageController;
use App\Http\Controllers\Api\CategoriesController;
use App\Http\Controllers\Api\TopicsController;
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

Route::prefix('v1')->name('api.v1.')
    ->group(function (){
        Route::middleware('throttle:'.config('api.rate_limits.sign'))//限制频率
            ->group(function (){
                Route::post('verifyicationCodes',[VerificationCodesController::class,'store'])
                    ->name('verificationCodes.store');
                Route::post('users',[UsersController::class,'store'])
                    ->name('UsersController.store');//注册
        });
        Route::middleware('throttle:'.config('api.rate_limits.sign'))//限制频率
        ->group(function (){
            Route::post('captchas',[CaptchasController::class,"store"])
            ->name("CaptchasController.store");//图片验证码
        });
        Route::post('login',[AuthorizationsController::class,'store'])->name('login.store');//登陆
        Route::put('authorizations/current',[AuthorizationsController::class,'update'])->name('authorizations.update');
        Route::put('authorizations/destory',[AuthorizationsController::class,'destory'])->name('authorizations.destory');

        //访客
        Route::get('users/{user}',[UsersController::class,'show'])->name('users.show');

        Route::middleware('auth:api')->group(function (){//需要登陆
            Route::get('user',[UsersController::class,'me'])->name('user.me');
            Route::post('images',[ImageController::class,'store'])->name('images.store');//上传图片
            Route::patch('user',[UsersController::class,'update'])->name('user.update');//更新
            //发布修改删除话题
            Route::apiResource('topics',TopicsController::class)->only(['store','update','destroy']);
        });
        Route::apiResource('categories',CategoriesController::class)->only('index');//分类列表
        Route::apiResource('topics',TopicsController::class)->only(['index','show']);//话题列表，详情
});
