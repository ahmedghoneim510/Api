<?php

namespace App\Http\Middleware;

use Closure;
use Exception;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Symfony\Component\HttpFoundation\Response;
use App\Traits\GeneralTrait;
class CheckAdminToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    use GeneralTrait;
    public function handle(Request $request, Closure $next): Response
    {

        // $user = null;
        // try {
        //     $user = JWTAuth::parseToken()->authenticate();
        //         //throw an exception

        // } catch (Exception $e) {
        //     if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenInvalidException){
        //         return response() -> json(['success' => false, 'msg' => 'INVALID _TOKEN']);
        //     }else if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenExpiredException){
        //         return response() -> json(['success' =>false, 'msg'=>'EXPIRED_TOKEN']);
        //     } else{
        //         return response() -> json(['success' => false, 'msg' => 'Error']);
        //     }
        // }

        // return $next($request);




        $user = null;
        try {
            $user = JWTAuth::parseToken()->authenticate();
        } catch (\Exception $e) {
            if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenInvalidException) {
                return $this ->returnError('E3001','INVALID_TOKEN');
            } else if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenExpiredException) {
                return $this ->returnError('E3001','EXPIRED_TOKEN');
            } else {
                return $this -> returnError('E3001','TOKEN_NOTFOUND');
            }
        } catch (\Throwable $e) {
            if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenInvalidException) {
                return $this -> returnError('E3001','INVALID_TOKEN');
            } else if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenExpiredException) {
                return $this->returnError('E3001','EXPIRED_TOKEN');
            } else {
                return $this->returnError('E3001','TOKEN_NOTFOUND');
            }
        }

        if (!$user){
            $this->returnError("E001","Unauthenticated");
        }
            // return response()->json(['success' => false, 'msg' => trans('Unauthenticated')], 200);
            // return $this->returnError('E331', trans('Unauthenticated'));
        return $next($request);
    }
}
