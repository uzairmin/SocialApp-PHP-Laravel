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
    function commenting(Request $request)
    {
        $user_id=0;
        $email = $request->email;
        $token = $request->token;
        $check = self::checkLogged($email,$token);
        if($check == true)
        {
            $users = DB::table('users')->where('email',$email)->get();
            foreach ($users as $user) 
            {
                $user_id = $user->id;
            }
        }
        $comment = new Comment();
        $comment->comment = $request->comment;
        $comment->file = $request->file('file')->store('comment');
        $comment->user_id = $user_id;
        $comment->post_id = $request->post_id;
        $comment->save();
    }
}
