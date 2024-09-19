<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\User;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    public function signinStore(Request $request)
    {
        try {
            $request->validate([
                'login' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:3|confirmed',
                'password_confirmation' => 'required|same:password',
            ]);

            $user = User::create([
                'login' => $request->input('login'),
                'email' => $request->input('email'),
                'password' => Hash::make($request->input('password'))
            ]);

            // Вход пользователя
            Auth::loginUsingId($user->id);
            return response()->json(['message' => 'User registered successfully.', 'user' => $user->login]);
        } catch (\Exception $e) {
            // Возвращение JSON-ответа с ошибкой
            return response()->json([
                'error' => 'An error occurred during registration. Please try again later.'
            ], 500);
        }
    }
}
