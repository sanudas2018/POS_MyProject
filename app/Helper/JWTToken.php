<?php

namespace App\Helper;

use Exception;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class JWTToken
{
    public static function CreateToken($userEmail): string
    {
        // evn file থেকে read করা নিলাম
        $key = env('JWT_KEY');
        $payload = [
            'iss' => 'laravel-token', 
            // Current Time add করা 
            'iat' => time(), 

            // Expire Time add করা 
            'exp' => time()+60*60,

            // User Email set করা. নিদিষ্ট ইউজার কে পাবার জন্য।
            'userEmail' => $userEmail
        ];
        return JWT::encode($payload, $key, 'HS256');
    }

    // এখানে password change করার Token পাবার পরে ঐ Token দিয়ে varify করার জন্য এটি করতে হবে।
    public static function CreateTokenForSetPassword($userEmail): string
    {
        // evn file থেকে read করা নিলাম
        $key = env('JWT_KEY');
        $payload = [
            'iss' => 'laravel-token', 
            // Current Time add করা 
            'iat' => time(), 

            // Expire Time add করা (5m Time)
            'exp' => time()+60*5,

            // User Email set করা. নিদিষ্ট ইউজার কে পাবার জন্য।
            'userEmail' => $userEmail
        ];
        return JWT::encode($payload, $key, 'HS256');
    }



    public static function verifyToken($token): string
    {
        try {
            $key = env('JWT_KEY');
            $decode = JWT::decode($token,new Key($key, 'HS256'));
            return $decode->userEmail;
        } catch (Exception $e) {
            return 'unauthorized';
        }
    }
}