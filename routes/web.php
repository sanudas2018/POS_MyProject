<?php

use App\Http\Controllers\DashboardController;
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
Route::post('/verify-otp', [UserController::class,'VerifyOTP']);

// Step-5: Reset Password & Token Verify
Route::post('/reset-password',[UserController::class,'ResetPassword'])->middleware([TokenVerificationMiddleware::class]); 



/* Front End - Page Routes 
   ------------------------------
*/
// Step-1: Login Page
Route::get('/userLogin', [UserController::class,'LoginPage']);

// Step-2: Registration Page
Route::get('/userRegistration', [UserController::class,'RegistrationPage']);

// Step-3: SendOTP Page
Route::get('/sendOtp', [UserController::class,'SendOtpPage']);

// Step-4: Verify OTP Page
Route::get('/verifyOtp', [UserController::class,'VerifyOTPPage']);

// Step-5: Reset Password Page
Route::get('/resetPassword',[UserController::class,'ResetPasswordPage'])->middleware([TokenVerificationMiddleware::class]); 

// Step-6: Dashboard Page
Route::get('/dashboard',[DashboardController::class,'DashboardPage'])->middleware([TokenVerificationMiddleware::class]);

// Step-7: Logout
Route::get('/logout', [UserController::class,'UserLogout'])->middleware([TokenVerificationMiddleware::class]);

// Step-8: Profile Page
Route::get('/userProfile', [UserController::class,'ProfilePage'])->middleware([TokenVerificationMiddleware::class]);








