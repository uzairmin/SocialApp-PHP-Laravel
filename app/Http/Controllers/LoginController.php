<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\mail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class LoginController extends Controller
{
    function jwtToken($email,$passwor)
    {   
        $key = "uzair";
        $payload = array(
            "iss" => "localhost",
            "aud" => time(),
            "iat" => now(),
            "nbf" => 1
        );

        $jwt = JWT::encode($payload, $key, 'HS256');
        $dbpass = DB::table('users')->where('email',$email)->get();
        $pass = $dbpass[0]->password;
        if (Hash::check($passwor, $pass)) 
        {
            DB::table('users')->where('email', $email)->where('password',$pass)->update(['remember_token'=>$jwt]);
            DB::table('users')->where('email', $email)->where('password',$pass)->update(['status'=>'1']);    
        }
    }
    function checkLogged($email,$token)
    {
        $data = DB::table('users')->where('email',$email)->where('remember_token',$token)->get();
        if(count($data) > 0)
        {
            return true;
        }
        return false;
    }
    function updateName(Request $request)
    {
        $email = $request->email;
        $token = $request->token;
        $newname = $request->newname;
        $check = self::checkLogged($email,$token);
        if($check == true)
        {
            DB::table('users')->where('email', $email)->where('remember_token',$token)->update(['name'=>$newname]);
        }
        else
        {
            echo "Wrong Email or token...";
        }
    }
    function updatePassword(Request $request)
    {
        $email = $request->email;
        $token = $request->token;
        $newpassword = Hash::make($request->newpassword);
        $check = self::checkLogged($email,$token);
        if($check == true)
        {
            DB::table('users')->where('email', $email)->where('remember_token',$token)->update(['password'=>$newpassword]);
        }
        else
        {
            echo "Wrong Email or token...";
        }
    }
    function updateGender(Request $request)
    {
        $email = $request->email;
        $token = $request->token;
        $gender = $request->gender;
        $check = self::checkLogged($email,$token);
        if($check == true)
        {
            DB::table('users')->where('email', $email)->where('remember_token',$token)->update(['gender'=>$gender]);
        }
        else
        {
            echo "Wrong Email or token...";
        }
    }
    function logingin(Request $request)
    {
        $email = $request->email;
        $password = $request->password;
        self::jwtToken($email,$password);
    }
}