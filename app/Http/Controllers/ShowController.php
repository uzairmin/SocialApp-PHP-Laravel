<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

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
                    $showFriends = DB::table('users')->where('id',$user)->get();
                }
                elseif($t1->user1_id == $userId)
                {
                    $user = $t1->user2_id;
                    $showFriends = DB::table('users')->where('id',$user)->get();
                } 
                foreach($showFriends as $sf)
                {
                    echo $sf->name;
                    echo"<br>";
                }
            }
        }
    }
    function showUser(Request $request)
    {
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
            $data = DB::table('users')->where('id',$userId)->get();
            foreach($data as $t)
            {
                echo $t->name;
                echo"<br>";
                echo $t->email;
                echo"<br>";
                echo $t->gender;
                echo"<br>";
            }
        }
    }
    function showPost(Request $request)
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
            $data1 = DB::table('posts')->where('user_id',$userId)->where('id',$postId)->get();
            foreach($data1 as $t1)
            {
                echo $t1->file;
                echo"<br>";
                echo $t1->access;
                echo"<br>";
            }
            $data2 = DB::table('comments')->where('user_id',$userId)->where('post_id',$postId)->get();
            foreach($data2 as $t2)
            {
                echo $t2->comment;
                echo"<br>";
                echo $t2->file;
                echo"<br>";
            }
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
            $data2 = DB::table('comments')->where('id',$commentId)->where('post_id',$postId)->get();
            foreach($data2 as $t2)
            {
                echo $t2->comment;
                echo"<br>";
                echo $t2->file;
                echo"<br>";
            }
        }
    }
}
