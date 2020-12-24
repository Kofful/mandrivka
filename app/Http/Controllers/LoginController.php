<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    public function login(Request $request) {
        $validator = Validator::make($request->all(),[
            'email' => ['required', 'email'],
            'password' => ['required', 'min:8']
            ]);
        if($validator->fails()) {
            $response = ['code' => '400', 'errors' => $validator->errors()];
            return response()->json($response);
        }
        $user = User::where('email', $request->email)->first();

        if(!$user || !Hash::check($request->password, $user->password)) {
            $response = ['code' => '400', 'errors' => ['login'=> ['Login credentials are incorrect.']]];
            return response()->json($response);
        }
        $response = ['code' => '200', 'token' => $user->createToken('Auth Token')->accessToken];
        return response()->json($response);
    }


    public function logout(Request $request) {
        $request->user()->tokens()->delete();
    }

    public function index() {
        return view('login');
    }
}
