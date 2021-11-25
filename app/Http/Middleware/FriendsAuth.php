<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class FriendsAuth
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
        $user_record = $request->user_data;
        if(!empty($user_record))
        {
            $email2 = $request->email2;
            $check = DB::table('users')->where('email',$email2)->get();
            if($check)
            {
                return $next($request);
            }
            else
            {
                return response()->json(['Message'=>"$email does not exist"]);
            }
        }   
    }
}
