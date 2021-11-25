<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class customAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $token = $request->token; 
        $data = DB::table('users')->where('remember_token',$token)->first();
        if($data)
        {
            return $next($request->merge(['user_data'=>$data]));
        }
        else
        {
            return response("Your are not Authenticated User. / Token Not Matched in Middleware.");
        }
    }
}
