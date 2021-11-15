<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\mail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Mail\TestMail;
use App\Http\Requests\SignupValidation;

class SignupController extends Controller
{
   
    function signingUp(SignupValidation $request)
    {
        $token = rand(1000,1000000);
        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->gender = $request->gender;
        $user->active = 1;
        $user->token = $token;
        $user->save();
        $details = ['title'=>'Verify to continue',
                'body'=>'http://127.0.0.1:8000/api/confirmation/'.$request->email.'/'.$token
            ];
        Mail::to($request->email)->send(new TestMail($details));
        return 'Mail Sent...';
    }
    public function checkLogged($email,$token)
    {
        $data = DB::table('users')->where('email',$email)->where('remember_token',$token)->get();
        if(count($data) > 0)
        {
            return true;
        }
        return false;
    }
    function deactivate(Request $request)
    {
        $email = $request->email;
        $token = $request->token;
        $check = self::checkLogged($email,$token);
        if($check == true)
        {
            DB::table('users')->where('email',$email)->where('remember_token',$token)->update(['active'=>null]);
        }
        else
        {
            echo "User is not authenticated";
        }
    }
}
