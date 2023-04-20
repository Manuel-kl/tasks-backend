<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function register(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);
        if ($validator->fails()) {
            $errors = $validator->errors();
            $message = '';
            if ($errors->has('email')) {
                $message .= 'The email field is required. ';
            }
            if ($errors->has('password')) {
                $message .= 'The password field is required.';
            }
            return response()->json([
                'status' => 'error',
                'message' => $message,
            ]);
        }
        $user = User::where('email', $request->email)->first();
        if ($user) {
            if ($user->deleted_at) {
                $user->password = Hash::make($request->password);
                $user->deleted_at = null;
                $user->save();
                $token = $user->createToken('authToken')->accessToken;
                return response([
                    'message' => 'Registration successful',
                    'user' => $user,
                    'access_token' => $token->token
                ]);
            } else {
                return response([
                    'message' => 'Email already exists'
                ]);
            }
        }
        $user = User::create([
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'deleted_at' => null
        ]);
        if ($user) {
            $token = $user->createToken('authToken')->accessToken;
            return response([
                'message' => 'Registration successful',
                'user' => $user,
                'access_token' => $token->token
            ]);
        } else {
            return response(['message' => 'Registration failed, please try again']);
        }
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);
        if ($validator->fails()) {
            $errors = $validator->errors();
            $message = '';
            if ($errors->has('email')) {
                $message .= 'The email field is required. ';
            }
            if ($errors->has('password')) {

                $message .= 'The password field is required';
            }
            return response()->json([
                'status' => 'error',
                'message' => $message,
            ]);
        }
        $user = User::where('email', $request->email)->first();
        if ($user) {
            if ($user->deleted_at) {
                return response([
                    'message' => 'Account was deleted, please register again'
                ]);
            }
            if (Hash::check($request->password, $user->password)) {
                $token = $user->createToken('authToken')->accessToken;
                return response([
                    'user' => $user, 'access_token' => $token->token
                ]);
            } else {
                return response([
                    'message' => 'Wrong password'
                ]);
            }
        } else {
            return response([
                'message' => 'User does not exist'
            ]);
        }
    }

    public function logout($id)
    {
        $user = User::find($id);
        if ($user) {
            $user->tokens()->delete();
            return response([
                'message' => 'Logged out'
            ]);
        } else {
            return response([
                'message' => 'User does not exist'
            ]);
        }
    }

    public function deleteAccount(Request $request, $id)
    {

        $user = User::find($id);
        if ($user) {
            $user->deleted_at = now();
            $user->tokens()->delete();
            $user->save();
            return response([
                'message' => 'Account deleted',
                'user' => $user
            ]);
        } else {
            return response([
                'message' => 'User does not exist'
            ]);
        }
    }
}
