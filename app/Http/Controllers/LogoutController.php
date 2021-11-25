<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\EmailValidation;
use Illuminate\Http\Request;

class LogoutController extends Controller
{
    function checkLogged($email,$token)
    {
        try
        {
            $check1 = DB::table('users')->where('email',$email)->where('remember_token',$token)->update(['status'=>'0']);
            $check2 = DB::table('users')->where('email',$email)->where('remember_token',$token)->update(['remember_token'=>null]);
            if($check1 == 1 && $check2 == 1)
            {
                return true;
            }
            return false;   
        }    
        catch(\Exception $show_error)    
        {        
            return response()->json(['Error' => $show_error->getMessage()], 500);    
        }
    }
    function loggingOut(EmailValidation $request)
    {
        try
        {
            $email = $request->email;
            $token = $request->token;
            $check = self::checkLogged($email,$token);
            if($check == true)
            {
                return response()->json(['Message'=>"Logged Out"]);
            }   
        }    
        catch(\Exception $show_error)    
        {        
            return response()->json(['Error' => $show_error->getMessage()], 500);    
        }
    }
}
