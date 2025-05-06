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
            'username' => 'required',
            'password' => 'required|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
            ]);
        }

        if (Auth::attempt($request->only('username', 'password'))) {
            $request->session()->regenerate();
            return response()->json([
                'success' => true,
                'redirect_url' => url('/')
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Invalid username or password.',
            ]);
        }

    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        Session::flash('logout_message', 'Successfully logged out');

        return response()->json(['message' => 'Successfully logged out']);
    }
}
