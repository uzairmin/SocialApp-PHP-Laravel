<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\mail;
use Illuminate\Support\Facades\Hash;
use App\Mail\TestMail;

class SignupController extends Controller
{
    function validation($request)
    {
        $val = Validator::make($request->all(),[
                        'name'=>'required|string',
                        'email'=>'required|string|email|unique:users',            
                        'password'=>'required|min:8',            
                        'gender'=>'required|Alpha'        
                    ]);
        if($val->fails())        
        {            
            return false;
        }
        return true;
    }
    function signingup(Request $req)
    {
        $check = self::validation($req);
        if($check==true)
        {
            $token = rand(1000,1000000);
            $user = new User();
            $user->name = $req->name;
            $user->email = $req->email;
            $user->password = Hash::make($req->password);
            $user->gender = $req->gender;
            $user->active = 1;
            $user->token = $token;
            $user->save();
            $details = ['title'=>'Verify to continue',
                    'body'=>'http://127.0.0.1:8000/api/confirmation/'.$req->email.'/'.$token
                ];
            Mail::to($req->email)->send(new TestMail($details));
            return 'Mail Sent...';
        }
        else
        {
            echo"Validation Failed...";
            die();
        }
    }
}
