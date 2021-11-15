<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class ShowFriendsController extends Controller
{
    function checkLogged($email1,$email2,$token)
    {
        $data = DB::table('users')->where('email',$email1)->where('remember_token',$token)->get();
        if(count($data) > 0)
        {
            return true;
        }
        return false;
    }
    function showFriends(Request $request)
    {
        $email = $request->email;
        $token = $request->token;
        $check = self::checkLogged($email,$token);
        if($check == true)
        {
            $data = DB::table('users')->where('email',$email1)->where('remember_token',$token)->get();
            foreach($data as $t)
            {
                $userId = $t->id;
            }
            $data1 = DB::table('friends')->where('user1_id',$userId)->get();
            foreach($data1 as $t1)
            {
                echo $t1->user2_id;
            }
        }
    }
}
