<?php

namespace App\Http\Controllers;

use App\Helper\JWTToken;
use App\Mail\OTPMail;
use App\Models\User;
use Exception;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class UserController extends Controller
{
    // All Page View Function

    function LoginPage():View{
        return view('pages.auth.login-page');
    }
    function RegistrationPage():View{
        return view('pages.auth.registration-page');
    }
    function SendOtpPage():View{
        return view('pages.auth.send-otp-page');
    }
    function VerifyOTPPage():View{
        return view('pages.auth.verify-otp-page');
    }
    function ResetPasswordPage():View{
        return view('pages.auth.reset-pass-page');
    }
    function ProfilePage():View{
        return view('pages.dashboard.profile-page');
    }


    // --------------END PAGE VIEW---------------




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
    function UserLogin(Request $request)
    {
       /* 
       প্রথম এভাবে করা হয়েছেঃ
       ----------------------
       $count = User::where('email', '=', $request->input('email'))
            ->where('password', '=', $request->input('password'))
            ->count();
        */
        /* Final এভাবে করতে হবে */ 
        $count = User::where('email', '=', $request->input('email'))
            ->where('password', '=', $request->input('password'))
            ->select('id')->first();
        if ($count !== null) {
            /*
            CreateToken এর ভিতরে দুটি parameter pass করতে হবে email, id
            */
            $token = JWTToken::CreateToken($request->input('email'), $count->id);
            return response()->json([
                'status' => 'success',
                'message' => 'User Login Successful',
                /*এই token টি কে আবার cookies এ দিতে হবে*/
                // 'token' => $token
            ], 200)->cookie('token', $token,60*24*30);
        } else {
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
    function SendOTPCode(Request $request)
    { 
        $email = $request->input('email');
        $otp = rand(1000, 9999);
        $count = User::where('email', '=', $email)->count();
        if ($count == 1) {
            // send OTP Code user email And Database table otp code update করতে হবে।
            // OTP Email Address send:
            Mail::to($email)->send(new OTPMail($otp));

            // OTP Code Table Update
            User::where('email', '=', $email)->update(['otp' => $otp]);

            return response()->json([
                'status' => 'success',
                'message' => '4 Digit OTP Code has been send to your email' 
            ], 200);
        } else {
            return response()->json([
                'status' => 'failed',
                'message' => 'Unauthorized' 
            ], 200);
        }
    }

    // VerifyOTP এখানে আগের SendOTPCode টি verify করা হবে।
    function VerifyOTP(Request $request) 
    {
        $email = $request->input('email');
        $otp = $request->input('otp');
        $count = User::where('email', '=', $email)
            ->where('otp', '=', $otp)->count();

        if ($count == 1) {
            // Database এ OTP টি কে Update করে দিতে হবে
            User::where('email','=',$email)->update(['otp'=>'0']);

            // Password reset Token Issue
            // Validation Time add করতে হবে।

            $token = JWTToken::CreateTokenForSetPassword($request->input('email'));

            return response()->json([
                'status' => 'success',
                'message' => 'OTP Verification Success',
                // 'token' => $token  
            ],200)->cookie('token', $token,60*24*30);
        } else {
            return response()->json([
                'status' => 'failed',
                'message' => 'Unauthorized'
            ], 501);
        }

    }

    // Reset Password & Token Verify 
    function ResetPassword(Request $request){ 
        try{
        $email = $request->header('email');
        $password = $request->input('password');
        User::where('email','=',$email)->update(['password'=>$password]);
        return response()->json([
            'status' => 'success',
            'message' => 'Request Successful'
        ],200);
        }catch(Exception $exception){
            return response()->json([
                'status' => 'fail',
                'message' => 'ResetPassword Wrong'
            ],401);
        }
    } 
    /*
        Password Reset option টি postman check করতে হলে যা করতে হবে ঃ 
        প্রথমে postman এর যেখানে POS Project BackEnd আছে শাখানে Variables এর ভিতরে PasswordResetToken এর নাম এবং token টি দিতে হবে। 
        এবার, user registration, user login, send otp, verify otp, করার পরে reset password করতে হবে এখানে Header এর ভিতরে key(token) এবং value( {{PasswordResetToken}} ) দিতে হবে। Token টি check করার জন্য।
    */
 
    // LogOut Process
    function UserLogout(){
        return redirect('/userLogin')->cookie('token', '', -1);
    }
    // Middleware এর মাধ্যমে - user যদি login না করে তা হলে তাকে Dashboard এ যেতে বাধা দিবে। এখানে email এর সাথে সাথে user id টি ও নিতে হবে। UserLogin function এর ভিতরে।

    // User Profile Data received and update
    function UserProfile(Request $request){
        // Security manage with Token verified
        $email = $request->header('email');
        $user = User::where('email','=', $email)->first();
        return response()->json([
            'status' => 'success',
            'message' => 'Request Successful User Profile',
            'data' => $user
        ],200);
    }

    // User Profile Update
    function UpdateProfile(Request $request){
        try{
            $email=$request->header('email');
            $firstName=$request->input('firstName');
            $lastName=$request->input('lastName');
            $mobile=$request->input('mobile');
            $password=$request->input('password');
            User::where('email','=',$email)->update([
                'firstName'=>$firstName,
                'lastName'=>$lastName,
                'mobile'=>$mobile,
                'password'=>$password
            ]);
            return response()->json([
                'status' => 'success',
                'message' => 'Request Successful',
            ],200);

        }catch (Exception $exception){
            return response()->json([
                'status' => 'fail',
                'message' => 'Something Went Wrong',
            ],200);
        }
    }


    


}
