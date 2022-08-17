<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\Controller;
use App\Http\Requests\Api\VerificationCodeRequest;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Request;
use Illuminate\Support\Str;


class VerificationCodesController extends Controller
{
    //
    public function store(VerificationCodeRequest $request){
        $captchaData = \Cache::get($request->captcha_key);
        if(!$captchaData){
            abort(403,'图片验证码失效');
        }
        if(!hash_equals(strtolower($request->captcha_code),strtolower($captchaData['code']))){
            \Cache::forget($request->captcha_key);
            throw new AuthenticationException('验证码错误');
        }
        $phone = $captchaData['phone'];
        if(!app()->environment('production')){
            $code = '1234';
        }else{
            $code = str_pad(random_int(1,9999),4,0,STR_PAD_LEFT);
        }
        $key = 'verificationCode_'.Str::random(15);
        $expiredAt = now()->addMinutes(5);
        \Cache::forget($request->captcha_key);
        \Cache::put($key,['phone'=>$phone,'code'=>$code],$expiredAt);
        return response()->json([
            'key'=>$key,
            'expired_at' => $expiredAt->toDateTimeString(),
        ])->setStatusCode(201);
    }
}
