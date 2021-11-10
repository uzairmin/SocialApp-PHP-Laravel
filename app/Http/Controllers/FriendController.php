<?php

namespace App\Http\Controllers;
use App\Models\Friend;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class FriendController extends Controller
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
    function addFriend(Request $request)
    {
        $u1_id;
        $u2_id;
        $email1 = $request->email1;
        $token = $request->token;
        $email2 = $request->email2;
        $check = self::checkLogged($email1,$email2,$token);
        if($check == true)
        {
            $users1 = DB::table('users')->where('email',$email1)->get();
            foreach ($users1 as $user1) 
            {
                $u1_id = $user1->id;
            }
            $users2 = DB::table('users')->where('email',$email2)->get();
            foreach ($users2 as $user2) 
            {
                $u2_id = $user2->id;
            }
        }
        $friend = new Friend();
        $friend->user1_id = $u1_id;
        $friend->user2_id = $u2_id;
        $friend->save();
    }
}
