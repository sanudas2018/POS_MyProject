<?php

namespace App\Http\Controllers;

use App\Helper\JWTToken;
use App\Mail\OTPMail;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

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


    // OTP Code send Function
        /*
    Sending OTP code to email- password recovery stage 1 (end point)
    app -> Mail -> OTPMail তৈরি হবে ঃ
    php artisan make:mail OTPMail
    
    app -> Helper -> JWTToken.php

    .env file এ domain mail server add, 

    OTP mail blade page তৈরি করতে হবে।
    route setup
    */
    function SendOTPCode(Request $request){
        $email = $request->input('email');
        $otp = rand(1000, 9999);
        $count = User::where('email','=',$email)->count();
        if($count == 1){
            // send OTP Code user email And Database table otp code update করতে হবে।
            // OTP Email Address send:
            Mail::to($email)->send(new OTPMail($otp));

            // OTP Code Table Update
            User::where('email','=',$email)->update(['otp'=>$otp]);

            return response()->json([
                'status' => 'success',
                'message' => '4 Digit OTP Code has been send to your email'
            ], 200);

        }else{
            return response()->json([
                'status' => 'failed',
                'message' => 'Unauthorized'
            ], 200);
        }


    }





















}
