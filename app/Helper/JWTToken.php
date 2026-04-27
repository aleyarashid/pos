<?php

namespace App\Helper;

use Exception;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class JWTToken
{
    public static function createToken($userEmail, $userId)
    {
        $key = env('JWT_KEY');
        $payload = [
            'iss' => 'laravel-token', // Issuer
            //'sub' => $user->id, // Subject (user ID)
            'iat' => time(), // Issued at
            'exp' => time() + 60 * 24 * 60, // Expiration time (1 hour)
            'userEmail' => $userEmail,
            'userId' => $userId
        ];

        return JWT::encode($payload, $key, 'HS256');
    }//end createToken

    //Token Verify
    public static function VerifyToken($token):string|object{
        try{
            if($token==null)
            {
                return 'unauthorized';
            }
            else{
                $key = env('JWT_KEY');
                $decoded = JWT::decode($token, new Key($key, 'HS256'));
                return $decoded;
            }

        }
        catch(Exception $e)
        {
            return 'unauthorized';
        }
    }//end of Token Verify

    //Create Token for set password
    public static function createTokenForSetPassword($userEmail)
    {
        $key = env('JWT_KEY');
        $payload = [
            'iss' => 'laravel-token', // Issuer
            //'sub' => $user->id, // Subject (user ID)
            'iat' => time(), // Issued at
            'exp' => time() + 60 * 24 * 60, // Expiration time (1 hour)
            'userEmail' => $userEmail,
            'userId' => '0'
        ];

        return JWT::encode($payload, $key, 'HS256');
    }//end createTokenfor set password
}