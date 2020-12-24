<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required'],
            'email' => ['required', 'email', 'unique:users'],
            'password' => ['required', 'min:8', 'confirmed']
        ]);
        if($validator->fails()) {
            $response = ['code' => '400', 'errors' => $validator->errors()];
            return response()->json($response);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ]);

        $response = ['code' => '200', 'token' => $user->createToken('Auth Token')->accessToken];
        return response()->json($response);
    }


    public function index() {
        return view('register');
    }
}
