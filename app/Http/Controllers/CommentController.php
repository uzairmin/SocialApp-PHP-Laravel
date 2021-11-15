<?php

namespace App\Http\Controllers;
use App\Models\Comment;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class CommentController extends Controller
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
    function checkFriends($userId,$postId)
    {
        $user = null;
        $data = DB::table('posts')->where('id',$postId)->get();
        foreach ($data as $d) 
        {
           $user = $d->user_id;   
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
    function checkAccess($postId)
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
    function commenting(Request $request)
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
        }
    }
    function updateFile(Request $request)
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
       }   
    }
    function updateComment(Request $request)
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
       }   
    }
    function deleteComment(Request $request)
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
       }   
    }
}
