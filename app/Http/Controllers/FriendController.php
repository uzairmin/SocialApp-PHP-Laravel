<?php

namespace App\Http\Controllers;
use App\Models\Friend;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Http\Requests\FriendsValidation;


class FriendController extends Controller
{
    function addFriend(FriendsValidation $request)
    {
        try
        {
            $u1Id;
            $u2Id;
            $email1 = $request->email1;
            $token = $request->token;
            $email2 = $request->email2;
            $users1 = DB::table('users')->where('email',$email1)->get();
            foreach ($users1 as $user1) 
            {
                $u1Id = $user1->id;
            }
            $users2 = DB::table('users')->where('email',$email2)->get();
            foreach ($users2 as $user2) 
            {
                $u2Id = $user2->id;
            }
            $user = DB::table('friends')->where('user1_id',$u1Id)->where('user2_id',$u2Id)->get();
            if(count($user) > 0)
            {
                return response()->json(['Message'=>"You both are already friends"]); 
            }
            else
            {
                $friend = new Friend();
                $friend->user1_id = $u1Id;
                $friend->user2_id = $u2Id;
                $friend->save();
                return response()->json(['Message'=>"You both are now friends"]);
            }
        }    
        catch(\Exception $show_error)    
        {        
            return response()->json(['Error' => $show_error->getMessage()], 500);    
        }
    }
    function unfriend(FriendsValidation $request)
    {
        try
        {
            $u1Id;
            $u2Id;
            $email1 = $request->email1;
            $token = $request->token;
            $email2 = $request->email2;
            $users1 = DB::table('users')->where('email',$email1)->get();
            foreach ($users1 as $user1) 
            {
                $u1Id = $user1->id;
            }
            $users2 = DB::table('users')->where('email',$email2)->get();
            foreach ($users2 as $user2) 
            {
                $u2Id = $user2->id;
            }
            $user = DB::table('friends')->where('user1_id',$u1Id)->where('user2_id',$u2Id)->get();
            if(count($user) > 0)
            {
                DB::table('friends')->where('user2_id',$u2Id)->delete();
                return response()->json(['Message' => "You both are no longer friends"], 200);
            }
            else
            {
                return response()->json(['Message' => "You both are not friends"], 500);
            }
        }    
        catch(\Exception $show_error)    
        {        
            return response()->json(['Error' => $show_error->getMessage()], 500);    
        }
    }
}
