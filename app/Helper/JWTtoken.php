<?php
namespace App\Helper;

use Exception;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class JWTtoken
{
public static function createTokaen($userEmail, $userId){
    $key = env("JWT_TOKAN");
    $payload = [
        "iss" => "laravel-token",
        "iat" => time(),
        "exp" => time()+60*60,
        "userEmail" => $userEmail,
        "userId" => $userId
    ];
    return JWT::encode($payload,$key,"HS256");
}

public static function verifyToken($token){
    try{
        if($token ==null){
            return "unauthorized";
        }
        else{
        $key = env("JWT_TOKAN");
        $decode = JWT::decode($token, new Key( $key, "HS256"));
        return $decode;
    }
    }

    catch(Exception $e){
        return "unauthorized";
    }
    
}

public static function CreateTokenForSetPassword($userEmail){
    $key = env("JWT_TOKAN");
    $payload = [
        "iss" => "laravel-token",
        "iat" => time(),
        "exp" => time()+60*20,
        "userEmail" => $userEmail,
        "userId" => "0"
    ];
    return JWT::encode($payload,$key,"HS256");
}

}