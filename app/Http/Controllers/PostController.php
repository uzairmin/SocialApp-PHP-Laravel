<?php

namespace App\Http\Controllers;
use App\Models\Post;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\PostValidation;
use App\Http\Requests\EmailValidation;
use App\Http\Requests\UpdateAccessValidation;
use Illuminate\Http\Request;

class PostController extends Controller
{
    function checkLogged($email,$token)
    {
        try
        {
            $data = DB::table('users')->where('email',$email)->where('remember_token',$token)->get();
            if(count($data) > 0)
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
    function posting(PostValidation $request)
    {
        try
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
                return response()->json(['Message'=>"Posted"]);
           } 
        }    
        catch(\Exception $show_error)    
        {        
            return response()->json(['Error' => $show_error->getMessage()], 500);    
        }
           
    }
    function updateFile(EmailValidation $request)
    {
        try
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
                return response()->json(['Message'=>"File updated"]);
           }      
        }    
        catch(\Exception $show_error)    
        {        
            return response()->json(['Error' => $show_error->getMessage()], 500);    
        }
        
    }
    function updateAccess(UpdateAccessValidation $request)
    {
        try
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
                return response()->json(['Message'=>"Access updated"]);
           }   
        }    
        catch(\Exception $show_error)    
        {        
            return response()->json(['Error' => $show_error->getMessage()], 500);    
        }   
    }
    function deletePost(EmailValidation $request)
    {
        try
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
                    return response()->json(['Message'=>"Post deleted"]);
                }
                else
                {
                    return response()->json(['Message'=>"No post available"]);
                }   
            }   
        }    
        catch(\Exception $show_error)    
        {        
            return response()->json(['Error' => $show_error->getMessage()], 500);    
        }
    }
}
