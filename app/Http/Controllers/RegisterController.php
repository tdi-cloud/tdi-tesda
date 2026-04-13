<?php

namespace App\Http\Controllers;

use App\Models\Otp;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use App\Notifications\SendOtpNotification;

class RegisterController extends Controller
{
    // Step 1: Validate form and send OTP
    public function sendOtp(Request $request)
    {
        $request->validate([
            'username' => 'required|string|max:100',
            'empcode'  => 'required|string|max:100|unique:users,empcode',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        // Generate 6-digit OTP
        $otp = rand(100000, 999999);

        // Delete old OTPs for this email
        Otp::where('email', $request->email)->delete();

        // Save OTP to database
        Otp::create([
            'email'      => $request->email,
            'otp'        => $otp,
            'expires_at' => now()->addMinutes(5), // expires in 5 mins
        ]);

        // Send OTP to email
        Notification::route('mail', $request->email)
            ->notify(new SendOtpNotification($otp));

        // Store registration data temporarily in session
        session([
            'register_data' => $request->only(
                'username', 'empcode', 'email', 'password'
            )
        ]);

        return redirect()->route('otp.verify.form')
            ->with('success', 'OTP sent to your email!');
    }

    // Step 2: Show OTP input form
    public function showOtpForm()
    {
        return view('auth.verify-otp');
    }

    // Step 3: Verify OTP and create account
    public function verifyOtp(Request $request)
    {
        $request->validate([
            'otp' => 'required|digits:6',
        ]);

        $registerData = session('register_data');

        if (!$registerData) {
            return redirect()->route('register')
                ->withErrors(['otp' => 'Session expired. Please register again.']);
        }

        // Find OTP record
        $otpRecord = Otp::where('email', $registerData['email'])
            ->where('otp', $request->otp)
            ->first();

        // Check if OTP exists
        if (!$otpRecord) {
            return back()->withErrors(['otp' => 'Invalid OTP.']);
        }

        // Check if OTP is expired
        if (now()->isAfter($otpRecord->expires_at)) {
            $otpRecord->delete();
            return back()->withErrors(['otp' => 'OTP has expired. Please register again.']);
        }

        // OTP matched — create the user
        $user = User::create([
            'username' => $registerData['username'],
            'empcode'  => $registerData['empcode'],
            'email'    => $registerData['email'],
            'password' => Hash::make($registerData['password']),
            'access'   => 'guest',
        ]);

        // Clean up
        $otpRecord->delete();
        session()->forget('register_data');

        // Login and redirect
        Auth::login($user);

        return redirect('/')->with('success', 'Account created successfully!');
    }

    public function checkEmpcode(Request $request){
        $request->validate([
            'empcode' => 'required|string',
        ]);
        
        $employee = \App\Models\employees::where('EMPCODE', $request->empcode)->first();

        if(!$employee){
            return response()->json([
                'found' => false,
                'message' => 'Employee coude not found',
            ]);
        }
         
        return response()->json([
            'found' => true,
            'empcode' => $employee->EMPCODE,
            'name' => $employee->FIRSTNAME.' '.$employee->LASTNAME,
            'position' => $employee->POSITION,
            'office' => $employee['OFFICE/DIVISION']
        ]); 
        
    }

}