<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\User;
use App\Mail\OtpSand;
use Firebase\JWT\JWT;
use App\Helper\JWTtoken;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class UserController extends Controller
{
    
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
    public function store(Request $request)
    {
        try{
            $request->validate([
                "firstName" => "required|string|max:50",
                "lastName" => "required|string|max:50",
                "password" => "required|string|min:6",
                "email" => "required|string|email|max:50|unique:users,email",
                "mobile" => "required|string|max:50"
            ]);
            User::create([
                "firstName" => $request->input("firstName"),
                "lastName" => $request->input("lastName"),
                "password" => Hash::make($request->input("password")),
                 //"password" => $request->input("password"),
                "email" => $request->input("email"),
                "mobile" => $request->input("mobile")
            ]);
    
            return response()->json([
                "status" => "success",
                "message" => "User Registration Successfully"
            ], 200);
        }catch(Exception $e){
            return response()->json([
                "status" => "Failed",
                "message" => $e->getMessage()
            ], 200);
        }
        
    }

    public function userLogein(Request $request){
        $request->validate([
            'email' => 'required|string|email|max:50',
            'password' => 'required|string|min:6',
        ]);
        
        $user = User::where('email', $request->input('email'))->first();
        
        if ($user && Hash::check($request->input('password'), $user->password)) {
            
            $token = JWTtoken::createTokaen($request->input('email'), $user->id);
            return response()->json([
                'status' => 'success',
                'message' => 'User Login Successful',
            ], 200)->cookie('token', $token, time() + 60 * 24 * 30);
        } else {
            
            return response()->json([
                'status' => 'failed',
                'message' => 'Unauthorized',
            ]);
        }
        
    }

    public function OTPSand(Request $request){
        try{
            $request->validate([
                'email' => 'required|string|email|max:50'
            ]);
            $email = $request->input("email");
            $otp = rand(1000,9999);
            $count = User::where("email", "=", $email)->count();
    
            if($count==1){
                Mail::to($email)->send(new OtpSand($otp));
                User::where("email","=",$email)->update(["otp"=>$otp]);
                return response()->json([
                    "status" => "success",
                    "message" => "4 Digit OTP Code has been send to your email !"
                ],200);
            }
    
            else{
                return response()->json([
                    "status" => "failed",
                    "message" => "unauthorized"
                ],200);
            }
        }catch (Exception $e){
            return response()->json(['status' => 'failed', 'message' => $e->getMessage()]);
        }
        
    }

    public function VerifyOTP(Request $request){
        $request->validate([
            'email' => 'required|string|email|max:50',
            'otp' => 'required|string|min:4'
        ]);
        $email = $request->input("email");
        $otp = $request->input("otp");
        $count = User::where("email", "=", $email)
        ->where("otp", "=", $otp)->count();

        if($count==1){
            User::where("email", "=", $email)->update(["otp"=>"0"]);
            $token = JWTtoken::CreateTokenForSetPassword($request->input("email"));
            return response()->json([
                "status" => "success",
                "message" => "OTP Verification Successful"
            ],200)->cookie("token",$token,time()+60*24*30);
        }
        else{
            return response()->json([
                "status" => "failed",
                "message" => "unauthorized"
            ],200);
        }
    }

    public function ResetPassword(Request $request){
        try{
            $request->validate([
                "password" => "required|string|min:6"
            ]);
            $email = $request->header("email");
            //dd($email);
            $password = Hash::make($request->input("password"));
            User::where("email","=",$email)->update(["password"=>$password]);
            return response()->json([
                "status" => "success",
                "message" => "Password Reset Successful",
            ], 200);
        }
        catch(Exception $e){
            
            return response()->json([
                "status" => "failed",
                "message" => $e->getMessage(),
            ],200);
        }
        
    }

    public function logOut(){
        return redirect("/userLogin")->cookie("token"," ",-1);
    }
    
    public function UserProfile(Request $request){
        $email = $request->header("email");
        $user = User::where("email", "=", $email)->first();
        return response()->json([
            "status" => "success",
            "message" => "Request Successful",
            "data" => $user
        ],200);
    }

    public function UpdateProfile(Request $request){
        try{
            $request->validate([
                "firstName" => "required|string|max:50",
                "lastName" => "required|string|max:50",
                "password" => "required|string|min:6",
                "mobile" => "required|string|max:50"
            ]);
            $email=$request->header('email');
            $firstName=$request->input('firstName');
            $lastName=$request->input('lastName');
            $mobile=$request->input('mobile');
            $password=Hash::make($request->input("password"));
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

        }catch (Exception $e){
            return response()->json([
                'status' => 'fail',
                'message' =>  $e->getMessage(),
            ],200);
        }
    }
}
