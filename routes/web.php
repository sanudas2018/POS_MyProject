<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CustomerController;
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

// Step-6: User Profile All Data
Route::get('/user-profile',[UserController::class,'UserProfile'])->middleware([TokenVerificationMiddleware::class]); 

// Step-6: User Profile Update
Route::post('/user-update',[UserController::class,'UpdateProfile'])->middleware([TokenVerificationMiddleware::class]); 









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


// Category Page Route:
Route::get('/categoryPage',[CategoryController::class,'CategoryPage'])->middleware([TokenVerificationMiddleware::class]);

// Category API
Route::post("/create-category",[CategoryController::class,'CategoryCreate'])->middleware([TokenVerificationMiddleware::class]);
Route::get("/list-category",[CategoryController::class,'CategoryList'])->middleware([TokenVerificationMiddleware::class]);
Route::post("/delete-category",[CategoryController::class,'CategoryDelete'])->middleware([TokenVerificationMiddleware::class]);
Route::post("/update-category",[CategoryController::class,'CategoryUpdate'])->middleware([TokenVerificationMiddleware::class]);
Route::post("/category-by-id",[CategoryController::class,'CategoryByID'])->middleware([TokenVerificationMiddleware::class]);


// Customer Page Route:
Route::get('/customerPage',[CustomerController::class,'CustomerPage'])->middleware([TokenVerificationMiddleware::class]);
// Customer API
Route::post("/create-customer",[CustomerController::class,'CustomerCreate'])->middleware([TokenVerificationMiddleware::class]);
Route::get("/list-customer",[CustomerController::class,'CustomerList'])->middleware([TokenVerificationMiddleware::class]);
Route::post("/delete-customer",[CustomerController::class,'CustomerDelete'])->middleware([TokenVerificationMiddleware::class]);
Route::post("/update-customer",[CustomerController::class,'CustomerUpdate'])->middleware([TokenVerificationMiddleware::class]);
Route::post("/customer-by-id",[CustomerController::class,'CustomerByID'])->middleware([TokenVerificationMiddleware::class]);


