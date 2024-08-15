<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function loginview()
    {
        return view('auth.login');
    }

    public function ajaxLogin(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
            ]);
        }

        if (Auth::attempt($request->only('email', 'password'))) {
            $request->session()->regenerate();
            return response()->json([
                'success' => true,
                'redirect_url' => url('/') // Change this to your desired redirect URL
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Invalid email or password.',
            ]);
        }

        // dd($request->all());
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Flash message to the session
        Session::flash('logout_message', 'Successfully logged out');

        return response()->json(['message' => 'Successfully logged out']);
    }
}
