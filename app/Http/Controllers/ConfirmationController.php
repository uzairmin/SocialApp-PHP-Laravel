<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class ConfirmationController extends Controller
{
    function confirming($email,$token)
    {
        try
        {
            $check = DB::table('users')->where('email', $email)->where('token',$token)->update(['email_verified_at'=>now()]);
            if($check == 1)
            {
                return response()->json(['Message'=>"Email verified"]);
            }
            else if($check == 0)
            {
                return response()->json(['Message'=>"Email is not verified"]);
            }   
        }    
        catch(\Exception $show_error)    
        {        
            return response()->json(['Error' => $show_error->getMessage()], 500);    
        }
    }
}