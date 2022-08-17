<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\CaptchaRequest;
use Gregwar\Captcha\CaptchaBuilder;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CaptchasController extends Controller
{
    //
    public function store(CaptchaRequest $request,CaptchaBuilder $captchaBuilder){
        $key = 'captcha:'.Str::random(15);
        $phone = $request->phone;
        $captch = $captchaBuilder->build();
        $expired = date('Y-m-d');
        \Cache::put($key,['phone'=>$phone,'code'=>$captch->getPhrase()],$expired);
        $result = [
            'key' => $key,
            'expiredAt' => $expired,
            'captch_img' => $captch->inline(),
        ];
        return response()->json($result)->setStatusCode(201);
    }
}
