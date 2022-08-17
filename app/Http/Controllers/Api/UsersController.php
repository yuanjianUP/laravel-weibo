<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Api\UserRequest;
use App\Http\Resources\UserResource;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UsersController extends Controller
{
    //
    public function store(UserRequest $request){
        $verifyData = \Cache::get($request->verification_key);
        if (!$verifyData){
            abort(403,'验证码已失效');
        }
        if (!hash_equals($verifyData['code'],$request->verification_code)){
            throw new AuthenticationException("验证码错误");
        }
        $user = User::Create([
            'name' => $request->name,
            'password' => Hash::make($request->password),
            'phone' => $verifyData['phone'],
        ]);
        \Cache::forget($request->verification_key);
        return new UserResource($user);
    }

    /**
     * 访客访问
     * @param User $user
     * @param Request $request
     * @return UserResource
     */
    public function show(User $user,Request $request){
        return new UserResource($user);
    }

    public function me(Request $request){
        return (new UserResource($request->user()))->showSensitiveFields();//jwt登陆的用user()就可以
    }

    /**
     * 更新用户
     * @param UserRequest $request
     * @return UserResource
     */
    public function update(UserRequest $request){
        $user = $request->user();
        $attributes = $request->only(['name','email','introduction']);
        $user->update($attributes);
        return (new UserResource($user))->showSensitiveFields();
    }
}
