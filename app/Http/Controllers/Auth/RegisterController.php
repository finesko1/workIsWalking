<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\User;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

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
            return response()->json(['message' => 'User registered successfully.']);
        }
        catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation failed.',
                'errors' => $e->errors()
            ], 422);
        }
            // Общая ошибка
        catch (\Exception $e) {
            return response()->json([
                'message' => 'An error occurred during registration. Please try again later.'
            ], 500);
        }
    }
}
