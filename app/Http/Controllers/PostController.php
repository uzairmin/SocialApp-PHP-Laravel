<?php

namespace App\Http\Controllers;
use App\Models\Post;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class PostController extends Controller
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
    function posting(Request $request)
    {
        $id;
        $email = $request->email;
        $tokens = $request->token;
        $check = self::checkLogged($email,$tokens);
        if($check == true)
        {
            $users = DB::table('users')->where('email',$email)->get();
            foreach ($users as $user) 
            {
                $id = $user->id;
            }
            $post = new Post();
            $post->file = $request->file('file')->store('post');
            $post->user_id = $id;
            $post->access =$request->access;
            $post->save();
       }   
    }
    function updateFile(Request $request)
    {
        $id;
        $email = $request->email;
        $tokens = $request->token;
        $postId = $request->postId;
        $check = self::checkLogged($email,$tokens);
        if($check == true)
        {
            $users = DB::table('users')->where('email',$email)->get();
            foreach ($users as $user) 
            {
                $id = $user->id;
            }
            $file = $request->file('file')->store('post');
            DB::table('posts')->where('user_id',$id)->where('id',$postId)->update(['file'=>$file]);
       }   
    }
    function updateAccess(Request $request)
    {
        $id;
        $email = $request->email;
        $tokens = $request->token;
        $postId = $request->postId;
        $access = $request->access;
        $check = self::checkLogged($email,$tokens);
        if($check == true)
        {
            $users = DB::table('users')->where('email',$email)->get();
            foreach ($users as $user) 
            {
                $id = $user->id;
            }
            DB::table('posts')->where('user_id',$id)->where('id',$postId)->update(['access'=>$access]);
       }   
    }
    function deletePost(Request $request)
    {
        $id;
        $email = $request->email;
        $tokens = $request->token;
        $postId = $request->postId;
        $check = self::checkLogged($email,$tokens);
        if($check == true)
        {
            $users = DB::table('users')->where('email',$email)->get();
            foreach ($users as $user) 
            {
                $id = $user->id;
            }
            $query = DB::table('posts')->where('user_id',$id)->where('id',$postId)->get();
            if(count($query) > 0)
            {
                DB::table('comments')->where('post_id',$postId)->delete();
                DB::table('posts')->where('user_id',$id)->where('id',$postId)->delete();
            }
            else
            {
                echo "No post available";
            }   
        }
    }
}
