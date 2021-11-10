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
}
