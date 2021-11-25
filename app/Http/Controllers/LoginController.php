<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\mail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\UpdateNameValidation;
use App\Http\Requests\LoginValidation;
use App\Http\Requests\UpdatePasswordValidation;
use App\Http\Requests\UpdateGenderValidation;
use App\Services\JWT_Service;

class LoginController extends Controller
{
    function jwtToken($email,$passwor)
    {   
        try
        {
            $jwt_conn = new JWT_Service();
            $jwt = $jwt_conn->get_jwt();
            $dbpass = DB::table('users')->where('email',$email)->get();
            $pass = $dbpass[0]->password;
            if (Hash::check($passwor, $pass)) 
            {
                DB::table('users')->where('email', $email)->where('password',$pass)->update(['remember_token'=>$jwt]);
                DB::table('users')->where('email', $email)->where('password',$pass)->update(['status'=>'1']);    
                return response()->json(['remember_token'=>$jwt , 'message'=> 'Successfuly Login']);
            }   
        }    
        catch(\Exception $show_error)    
        {        
            return response()->json(['Error' => $show_error->getMessage()], 500);    
        }
    }
    function updateName(UpdateNameValidation $request)
    {
        try
        {
            $email = $request->email;
            $token = $request->token;
            $newname = $request->newname;
            if($check == true)
            {
                DB::table('users')->where('email', $email)->where('remember_token',$token)->update(['name'=>$newname]);
                return response()->json(['Message'=>"Name Updated"]);
            }
            else
            {
                return response()->json(['Message'=>"Wrong email or token"]);
            }   
        }    
        catch(\Exception $show_error)    
        {        
            return response()->json(['Error' => $show_error->getMessage()], 500);    
        }
    }
    function updatePassword(UpdatePasswordValidation $request)
    {
        try
        {
            $user_record = $request->user_data;
            $email = $request->email;
            $token = $request->token;
            $oldpassword = Hash::make($request->oldpassword);
            $newpassword = Hash::make($request->newpassword);
            if(!empty($user_record))
            {
                if(Hash::check($newpassword,$oldpassword))
                {
                    return response()->json(['Message'=>"Old password"]); 
                }
                else
                {
                    $pass = $user_record->password;
                    DB::table('users')->where('email', $email)->where('remember_token',$token)->update(['password'=>$newpassword]);    
                    return response()->json(['Message'=>"Password Updated"]);
                }
            }   
        }    
        catch(\Exception $show_error)    
        {        
            return response()->json(['Error' => $show_error->getMessage()], 500);    
        }
    }
    function updateGender(UpdateGenderValidation $request)
    {
        try
        {
            $user_record = $request->user_data;
            $email = $request->email;
            $token = $request->token;
            $gender = $request->gender;
            if($check == true)
            {
                DB::table('users')->where('email', $email)->where('remember_token',$token)->update(['gender'=>$gender]);
                return response()->json(['Message'=>"Gender updated"]);
            }
            else
            {
                return response()->json(['Message'=>"Wrong email or token"]);
            }   
        }    
        catch(\Exception $show_error)    
        {        
            return response()->json(['Error' => $show_error->getMessage()], 500);    
        }
    }
    function checkVerification($email)
    {
        try
        {
            $email_verified = NULL;
            $data = DB::table('users')->where('email',$email)->first();
            $email_verified = $data->email_verified_at;
            if($email_verified!=NULL)
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
    function loggingIn(LoginValidation $request)
    {
        try
        {
            $email = $request->email;
            $password = $request->password;
            $ver = self::checkVerification($email);
            if($ver == true)
            {
                return self::jwtToken($email,$password);
            }
            return response()->json(['Message'=>"Email is not verified"]);   
        }    
        catch(\Exception $show_error)    
        {        
            return response()->json(['Error' => $show_error->getMessage()], 500);    
        }
    }
}
