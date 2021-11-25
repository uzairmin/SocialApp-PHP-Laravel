<?php

namespace App\Http\Controllers;
use App\Models\Comment;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\EmailValidation;
use Illuminate\Http\Request;

class CommentController extends Controller
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
    function checkFriends($userId,$postId)
    {
        try
        {
            $user = null;
            $data = DB::table('posts')->where('id',$postId)->get();
            foreach ($data as $d) 
            {
            $user = $d->user_id;   
            }
            if($user==$userId)
            {
                return true;
            }
            $data1 = DB::table('friends')->where('user1_id',$userId)->where('user2_id',$user)->get();
            if(count($data1) > 0)
            {
                return true;
            }
            $data2 = DB::table('friends')->where('user2_id',$userId)->where('user1_id',$user)->get();
            if(count($data1) > 0)
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
    function checkAccess($postId)
    {
        try
        {
            $access = null;
            $users = DB::table('posts')->where('id',$postId)->get();
            foreach ($users as $user) 
            {
            $access = $user->access;    
            }
            if($access == "private")
            {
                return true;
            }
            else
            {
                return false;
            }
        }    
        catch(\Exception $show_error)    
        {        
            return response()->json(['Error' => $show_error->getMessage()], 500);    
        }
    }
    function commenting(EmailValidation $request)
    {
        try
        {
            $userId = 0;
            $ch;
            $email = $request->email;
            $token = $request->token;
            $check = self::checkLogged($email,$token);
            if($check == true)
            {
                $users = DB::table('users')->where('email',$email)->get();
                foreach ($users as $user) 
                {
                    $userId = $user->id;    
                }
            }
            $postId = $request->post_id;
            $flag = self::checkAccess($postId);
            if($flag = true)
            {
                $ch = self::checkFriends($userId,$postId);
                if($ch == true)
                {
                    $comment = new Comment();
                    $comment->comment = $request->comment;
                    $comment->file = $request->file('file')->store('comment');
                    $comment->post_id = $postId;
                    $comment->user_id = $userId;
                    $comment->save();
                    return response()->json(['Message'=>"Commented"]);
                }
            }
            else
            {
                $comment = new Comment();
                    $comment->comment = $request->comment;
                    $comment->file = $request->file('file')->store('comment');
                    $comment->post_id = $postId;
                    $comment->user_id = $userId;
                    $comment->save();
                    return response()->json(['Message'=>"Commented"]);
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
            $file = $request->file('file')->store('comment');
            $postId = $request->postId;
            $commentId = $request->commentId;
            $check = self::checkLogged($email,$tokens);
            if($check == true)
            {
                $users = DB::table('users')->where('email',$email)->get();
                foreach ($users as $user) 
                {
                    $id = $user->id;
                }
                DB::table('comments')->where('user_id',$id)->where('post_id',$postId)->where('id',$commentId)->update(['file'=>$file]);
                return response()->json(['Message'=>"File Updated"]);
            }   
        }    
        catch(\Exception $show_error)    
        {        
            return response()->json(['Error' => $show_error->getMessage()], 500);    
        }
    }
    function updateComment(EmailValidation $request)
    {
        try
        {
            $id;
            $email = $request->email;
            $tokens = $request->token;
            $comment = $request->comment;
            $postId = $request->postId;
            $commentId = $request->commentId;
            $check = self::checkLogged($email,$tokens);
            if($check == true)
            {
                $users = DB::table('users')->where('email',$email)->get();
                foreach ($users as $user) 
                {
                    $id = $user->id;
                }
                DB::table('comments')->where('user_id',$id)->where('post_id',$postId)->where('id',$commentId)->update(['comment'=>$comment]);
                return response()->json(['Message'=>"Comment Updated"]);
            }
        }    
        catch(\Exception $show_error)    
        {        
            return response()->json(['Error' => $show_error->getMessage()], 500);    
        }   
    }
    function deleteComment(EmailValidation $request)
    {
        try
        {
            $id;
            $email = $request->email;
            $tokens = $request->token;
            $commentId = $request->commentId;
            $check = self::checkLogged($email,$tokens);
            if($check == true)
            {
                $users = DB::table('users')->where('email',$email)->get();
                foreach ($users as $user) 
                {
                    $id = $user->id;
                }
                DB::table('comments')->where('id',$commentId)->delete();
                return response()->json(['Message'=>"Comment Deleted"]);
            }   
        }    
        catch(\Exception $show_error)    
        {        
            return response()->json(['Error' => $show_error->getMessage()], 500);    
        }
    }
}
