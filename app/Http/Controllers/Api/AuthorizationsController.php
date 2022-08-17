<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\AuthorizationRequest;
use Illuminate\Http\Request;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Support\Facades\Auth;
class AuthorizationsController extends Controller
{

    //
    public function store(AuthorizationRequest $request){
        $username = $request->username;
        filter_var($username,FILTER_VALIDATE_EMAIL) ?
            $lData['email'] = $request->username :
            $lData['phone'] = $request->username;
        $lData['password'] = $request->password;
        if(!$token = Auth::guard('api')->attempt($lData)){
            throw new AuthenticationException('用户名或密码错误');
        }
        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
            'expires_in' => Auth::guard('api')->factory()->getTTL()*60
        ])->setStatusCode(201);
    }

    /**
     * 更新token
     * @return mixed
     */
    public function update(){
        $token = auth('api')->refresh();
        return $this->respondWithToken($token);
    }

    /**
     * 删除token
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function destory(){
        auth('api')->destory();
        return response(null,204);
    }

    public function respondWithToken($token){
        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
            'expires_in' => auth('api')->factory()->getTTL()*60
        ]);
    }
}
