<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Models\OauthAccessToken;
use App\Models\User;
use Illuminate\Foundation\Auth\User as AuthUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $login = $request->validate([
            "email" => "required|email|exists:users",
            "password" => "required"
        ]);

        if (!Auth::attempt($login)) {
            return response(["message_en" => "Invalid Password", "message_ar" => "رمز مرور خاطئ"], 401);
        }
        $user = User::where("id", Auth::user()->id)->get()->first();
 
        if ($user) {
            if ($user->app_user_type>0) {
                $accessToken = Auth::user()->createToken("authToken")->accessToken;
                return response(["user" => $user, "access_token" => $accessToken]);
            }
             
        }
        return response(["message_en" => "Invalid Password", "message_ar" => "رمز مرور خاطئ"], 401);

    } //login

    public function update_password(Request $request)
    {
        $request->validate([
            "password" => "required",
 
         
        ]);
 
  
        $user = User::find(Auth()->id());
        $user->password = bcrypt($request->password);
        $user->save();
if($request->logout_all)
{
            OauthAccessToken::where('user_id', $user->id)->update(array('revoked' => 1));
}

        if (!$user) {
            // if ($user->info->user_type == 1 && $user->info->status == 0) {
                return response(["message_en" => "Something went wrong", "message_ar" => "الموافقة على الحساب معلقة"], 402);

            // }
        }
        Auth::guard('web')->attempt(["email" => $user->email, "password" => $request->password]);
        $accessToken = Auth::user()->createToken("authToken")->accessToken;
 
        return response(["user" => $user, "access_token" => $accessToken]);




    }
}
