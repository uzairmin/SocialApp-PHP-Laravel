<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;

use Illuminate\Http\Request;

class ConfirmationController extends Controller
{
    function confirming($email,$token)
    {
        $check = DB::table('users')->where('email', $email)->where('token',$token)->update(['email_verified_at'=>now()]);
        if($check == 1)
        {
            echo"Email Verified...";
        }
        else if($check == 0)
        {
            echo"Email is not verified...";
        }
    }
}