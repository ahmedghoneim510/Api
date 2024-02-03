<?php

namespace App\Http\Controllers\Api\Admin;

use Validator;
use App\Traits\GeneralTrait;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    use GeneralTrait;
    public function login(Request $request){
        //return ($request);
        try{
            $rules = [
                "password" => "required",
                "email" => "required",

            ];

            $validator=Validator::make($request->all(),$rules);

            if ($validator->fails()) {
                $code = $this->returnCodeAccordingToInput($validator);
                return $this->returnValidationError($code, $validator);
            }

            $credentials = $request->only(['email', 'password']);
            $token = Auth::guard('admin-api')->attempt($credentials);
            //return $token;
            if (!$token)
                return $this->returnError('E001', 'بيانات الدخول غير صحيحة');

            $admin = Auth::guard('admin-api')->user();
            $admin->api_token = $token;
            //return token
            return $this->returnData('admin', $admin);



        }catch(\Exception $ex){
            return $this->returnError($ex->getCode(),$ex->getMessage());
        }


    }
    public function logout(Request $request)
    {
         $token = $request->header('auth-token');
        if($token){

            try {
                JWTAuth::setToken($token)->invalidate();
                return $this->returnSuccessMessage('Logged out successfully');
            }catch (\Tymon\JWTAuth\Exceptions\TokenInvalidException $e){
                return  $this -> returnError('','some thing went wrongs');
            }

        }else{
            $this->returnError('','some thing went wrongs');
        }

    }
}
