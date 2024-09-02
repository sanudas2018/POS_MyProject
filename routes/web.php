<?php

use App\Http\Controllers\UserController;
use App\Http\Middleware\TokenVerificationMiddleware;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
/*
    Back End Route
*/
// Step-1: User Registration 
Route::post('/user-registration',[UserController::class,'UserRegistration']);

// Step-2: User login
Route::post('/user-login',[UserController::class,'UserLogin']);

// Step-3: OTP Code Send
Route::post('/send-otp',[UserController::class,'SendOTPCode']);

// Step-4: Verify OTP
Route::post('/verify-otp',[UserController::class,'VerifyOTP']);

// Step-5: Reset Password & Token Verify
Route::post('/reset-password',[UserController::class,'ResetPassword'])->middleware([TokenVerificationMiddleware::class]);


