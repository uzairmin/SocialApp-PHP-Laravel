<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Services\ConnectionDB;
use App\Http\Requests\EmailValidation;

class ShowController extends Controller
{
    function checkLogged($email,$token)
    {
        $data = DB::table('users')->where('email',$email)->where('remember_token',$token)->get();
        if(count($data) > 0)
        {
            return true;
        }
        return false;
    }
    function showFriends(Request $request)
    {
        $showFriend;
        $userId;
        $user;
        $email = $request->email;
        $token = $request->token;
        $check = self::checkLogged($email,$token);
        if($check == true)
        {
            $data = DB::table('users')->where('email',$email)->where('remember_token',$token)->get();
            foreach($data as $t)
            {
                $userId = $t->id;
            }
            $data1 = DB::table('friends')->where('user1_id',$userId)->orWhere('user2_id',$userId)->get();
            foreach($data1 as $t1)
            {
                if($t1->user2_id == $userId)
                {
                    $user = $t1->user1_id;
                    $showFriend = DB::table('users')->select('name')->where('id',$user)->get();
                }
                elseif($t1->user1_id == $userId)
                {
                    $user = $t1->user2_id;
                    $showFriend = DB::table('users')->select('name')->where('id',$user)->get();
                } 
                return response(['Your Friend is :'=>$showFriend]);
            }
        }
    }
    function showUser(EmailValidation $request)
    {
        $email = $request->email;
        $token = $request->token;
        $check = self::checkLogged($email,$token);
        if($check == true)
        {
            $data = DB::table('users')->select(['name','email','gender'])->where(['email'=> $email ,'remember_token'=>$token])->get();
            return response(['User Data :'=>$data]);
        }
        else
        {
            return response()->json(['Message'=>"Wrong Email or token"]);
        }
    }
    function showPost(EmailValidation $request)
    {
        $email = $request->email;
        $token = $request->token;
        $postId = $request->post_id;
        $check = self::checkLogged($email,$token);
        if($check == true)
        {
            $data = DB::table('users')->where('email',$email)->where('remember_token',$token)->get();
            foreach($data as $t)
            {
                $userId = $t->id;
            }
            $data1 = DB::table('posts')->select(['file','access'])->where(['user_id'=>$userId,'id'=>$postId])->get();
            $data2 = DB::table('comments')->select()->where(['user_id'=>$userId,'post_id'=>$postId])->get();
            return response(['Post Data :'=>$data1,'Comments Data :'=>$data2]);
        }
        else
        {
            return response()->json(['Message'=>"Wrong Email or token"]);
        }
    }
    function showComments(Request $request)
    {
        $email = $request->email;
        $token = $request->token;
        $postId = $request->post_id;
        $commentId = $request->comment_id;
        $check = self::checkLogged($email,$token);
        if($check == true)
        {
            $data = DB::table('users')->where('email',$email)->where('remember_token',$token)->get();
            foreach($data as $t)
            {
                $userId = $t->id;
            }
            $data2 = DB::table('comments')->select('comment','file')->where(['id'=>$commentId,'post_id'=>$postId])->get();
            return response(['Comments Data :'=>$data2]);
        }
        else
        {
            return response()->json(['Message'=>"Wrong Email or token"]);
        }
    }
}
