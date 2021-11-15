<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class LogoutController extends Controller
{
    function checkLogged($email,$token)
    {
        $check1 = DB::table('users')->where('email',$email)->where('remember_token',$token)->update(['status'=>'0']);
        $check2 = DB::table('users')->where('email',$email)->where('remember_token',$token)->update(['remember_token'=>null]);
        if($check1 == 1 && $check2 == 1)
        {
            return true;
        }
        return false;
    }
    function loggingOut(Request $request)
    {
        $email = $request->email;
        $token = $request->token;
        $check = self::checkLogged($email,$token);
        if($check == true)
        {
            echo"Logged Out";
        }
        else{}
    }
}
