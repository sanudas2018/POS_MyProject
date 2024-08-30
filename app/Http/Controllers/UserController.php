<?php

namespace App\Http\Controllers;

use App\Helper\JWTToken;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;

class UserController extends Controller
{
    // User Registration Function
    function UserRegistration(Request $request)
    {
        // User try catch 
        try {
            // input থেকে Data নিয়ে User model মাধ্যমে Data কে Database এ দিয়ে দিবে। 
            User::create([
                'firstName' => $request->input('firstName'),
                'lastName' => $request->input('lastName'),
                'email' => $request->input('email'),
                'mobile' => $request->input('mobile'),
                'password' => $request->input('password'),
            ]);

            // User Registration হবে, পরে message show করার জন্য। এটি Front End থেকে ব্যাবহার করা যেতে পারে।
            return response()->json([
                'status' => 'success',
                'message' => 'User Registration Successfully'
            ], 200);
        } catch (Exception $error) {
            //throw $error;
            return response()->json([
                'status' => 'failed',
                // 'message' => 'User registration Failed'
                'message' => $error->getMessage()
            ], 200);
        }
    }

    // JWT Token Control
    // User Login
    function UserLogin(Request $request){
        $count = User::where('email','=',$request->input('email'))
        ->where('password','=',$request->input('password'))
        ->count();
        if($count == 1){
            // User Login হবে JWT Token নিয়ে
            $token = JWTToken::CreateToken($request->input('email'));
            return response()->json([
                'status' => 'success',
                'message' => 'User Login Successful',
                'token' => $token
            ], 200);
        }else{
            return response()->json([
                'status' => 'failed',
                'message' => 'Unauthorized'
            ], 200);
        }
    }

}
