<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['<PASSWORD>'],
        ]);

        if (auth()->attempt($credentials)) {
            $token = auth()->user()->createToken('Personal Access Token', ['auth'])->plainTextToken;

            return response()->json([
                'token' => $token,
            ]);
        }

        return response()->json([
           'message' => 'Invalid credentials',
        ], 401);
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
           'fullname' => ['required','string','max:255'],
           'email' => ['required','string', 'email','max:255', 'unique:users'],
           'password' => ['required','string','min:8', 'confirmed'],
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $user = User::create([
            'email' => $request->email,
            'fullname' => $request->fullname,
            'password' => bcrypt($request->password),
        ]);

        $token = $user->createToken('Personal Access Token', ['auth'])->plainTextToken;

        return response()->json([
            'token' => $token,
        ], 200);
    }
}
