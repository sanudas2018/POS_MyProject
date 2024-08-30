<?php

namespace App\Http\Controllers;

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
}
