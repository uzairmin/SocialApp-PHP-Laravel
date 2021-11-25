<?php

namespace App\Services;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Support\Facades\Config;


class JWT_Service
{
    public function get_jwt()    
    {        
        // jwt token generate        
        $key = Config::get('Constant.Key');        
        $payload = array(         
            "iss" => "localhost",            
            "aud" => time(),            
            "iat" => now(),            
            "nbf" => 1        
        );                
        $jwt = JWT::encode($payload, $key, 'HS256');
        return $jwt;
    }
}