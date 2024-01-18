<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DashboardController;
use App\Http\Middleware\TokenVerificationMiddleware;

// Web API Routes
Route::post("/user-registration",[UserController::class, "store"]);
Route::post("/user-login",[UserController::class, "userLogein"]);
Route::post("/send-otp",[UserController::class, "OTPSand"]);
Route::post("/verify-otp",[UserController::class, "VerifyOTP"]);
Route::get("/user-profile",[UserController::class, "UserProfile"])->middleware([TokenVerificationMiddleware::class]);
Route::post("/user-update",[UserController::class, "UpdateProfile"])->middleware([TokenVerificationMiddleware::class]);


Route::post("reset-password",[UserController::class, "ResetPassword"])
    ->middleware([TokenVerificationMiddleware::class]);

//logout
Route::get("/log-out",[UserController::class,"logOut"]);

//page view
Route::get("/",[HomeController::class,"index"]);
Route::get("/userLogin",[UserController::class,"LoginPage"]);
Route::get("/userRegistration",[UserController::class,"RegistrationPage"]);
Route::get("/sendOtp",[UserController::class,"SendOtpPage"]);
Route::get("/verifyOtp",[UserController::class,"VerifyOTPPage"]);
Route::get("/dashboard",[DashboardController::class,"DashboardPage"])->middleware([TokenVerificationMiddleware::class]);
Route::get("/resetPassword",[UserController::class,"ResetPasswordPage"])->middleware([TokenVerificationMiddleware::class]);
Route::get("/userProfile",[UserController::class,"ProfilePage"])->middleware([TokenVerificationMiddleware::class]);
Route::get("/categoryPage",[CategoryController::class,"index"])->middleware([TokenVerificationMiddleware::class]);
Route::get("/customerPage",[CustomerController::class,"index"])->middleware([TokenVerificationMiddleware::class]);
Route::get("/productPage",[ProductController::class,"index"])->middleware([TokenVerificationMiddleware::class]);
Route::get("/invoicePage",[InvoiceController::class,"InvoicePage"])->middleware([TokenVerificationMiddleware::class]);
Route::get("/salePage",[InvoiceController::class,"SalePage"])->middleware([TokenVerificationMiddleware::class]);
Route::get('/reportPage',[ReportController::class,'ReportPage'])->middleware([TokenVerificationMiddleware::class]);

 // Category api
Route::post("/create-category",[CategoryController::class,"store"])->middleware([TokenVerificationMiddleware::class]);
Route::get("/category-list",[CategoryController::class,"categoryList"])->middleware([TokenVerificationMiddleware::class]);
Route::post("/category-update",[CategoryController::class,"update"])->middleware([TokenVerificationMiddleware::class]);
Route::post("/category-destroy",[CategoryController::class,"destroy"])->middleware([TokenVerificationMiddleware::class]);
Route::post("/category-edit",[CategoryController::class,"edit"])->middleware([TokenVerificationMiddleware::class]);

// Customer api
Route::post("/create-customer",[CustomerController::class,"store"])->middleware([TokenVerificationMiddleware::class]);
Route::post("/customer-update",[CustomerController::class,"update"])->middleware([TokenVerificationMiddleware::class]);
Route::post("/customer-destroy",[CustomerController::class,"destroy"])->middleware([TokenVerificationMiddleware::class]);
Route::post("/customer-edit",[CustomerController::class,"edit"])->middleware([TokenVerificationMiddleware::class]);
Route::get("/customer-list",[CustomerController::class,"show"])->middleware([TokenVerificationMiddleware::class]);

// Product api
Route::post("/create-product",[ProductController::class,"store"])->middleware([TokenVerificationMiddleware::class]);
Route::post("/product-edit",[ProductController::class,"edit"])->middleware([TokenVerificationMiddleware::class]);
Route::post("/product-destroy",[ProductController::class,"destroy"])->middleware([TokenVerificationMiddleware::class]);
Route::post("/product-update",[ProductController::class,"update"])->middleware([TokenVerificationMiddleware::class]);
Route::get("/product-list",[ProductController::class,"show"])->middleware([TokenVerificationMiddleware::class]);

//invoice api
Route::post("/create-invoice",[InvoiceController::class,"create"])->middleware([TokenVerificationMiddleware::class]);
Route::get("/select-invoice",[InvoiceController::class,"select"])->middleware([TokenVerificationMiddleware::class]);
Route::post("/invoice-details",[InvoiceController::class,"details"])->middleware([TokenVerificationMiddleware::class]);
Route::post("/invoice-destroy",[InvoiceController::class,"destroy"])->middleware([TokenVerificationMiddleware::class]);


// SUMMARY & Report
Route::get("/summary",[DashboardController::class,"Summary"])->middleware([TokenVerificationMiddleware::class]);
Route::get("/sales-report/{FormDate}/{ToDate}",[ReportController::class,'SalesReport'])->middleware([TokenVerificationMiddleware::class]);