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
use App\Providers\ResponseServiceProviders;
use App\Jobs\MailJob;

class SignupController extends Controller
{
   
    function signingUp(SignupValidation $request)
    {
        try
        {
            $token = rand(1000,1000000);
            $user = new User();
            $user->name = $request->name;
            $email = $request->email;
            $user->email = $email;
            $user->password = Hash::make($request->password);
            $user->gender = $request->gender;
            $user->active = 1;
            $user->token = $token;
            $user->save();
            $details = ['title'=>'Verify to continue',
                    'body'=>'http://127.0.0.1:8000/api/confirmation/'.$request->email.'/'.$token
                ];
                //$mail = new MailJob($email, $details);
                dispatch(new MailJob($email, $details));
            //Mail::to($request->email)->send(new TestMail($details));
            return response()->json(['Message'=>"Mail Sent"]);  
        }    
        catch(\Exception $show_error)    
        {        
            return response()->json(['Error' => $show_error->getMessage()], 500);    
        }
    }
    public function checkLogged($email,$token)
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
    function deactivate(EmailValidation $request)
    {
        try
        {
            $email = $request->email;
            $token = $request->token;
            $check = self::checkLogged($email,$token);
            if($check == true)
            {
                DB::table('users')->where('email',$email)->where('remember_token',$token)->update(['active'=>null]);
                return response()->json(['Message'=>"User deactivated"]);
            }
            else
            {
                return response()->json(['Message'=>"User is not authenticated"]);
            }
        }    
        catch(\Exception $show_error)    
        {        
            return response()->json(['Error' => $show_error->getMessage()], 500);    
        }
    }
}
