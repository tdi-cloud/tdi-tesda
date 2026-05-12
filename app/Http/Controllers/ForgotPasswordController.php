<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;

class ForgotPasswordController extends Controller
{
    public function index(){
        return view("auth.forgot-password");
    }

    public function sendLink(Request $request){
        $request->validate([
            "email"=> "required|email|exists:users,email",   
        ]);

        $status= Password::sendResetLink(
            $request->only('email')
        );

        return $status === Password::RESET_LINK_SENT
        ? back()->with('success','Reset link sent to your email')
        : back()->withErrors(['email' => 'We could not find a user with that email.']);
    }
}
