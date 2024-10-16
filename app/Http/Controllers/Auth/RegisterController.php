<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class RegisterController extends Controller
{
    public function signinStore(Request $request)
    {
        try {
            $request->validate([
                'login' => 'required|string|max:255|unique:users',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:8|confirmed',
                'password_confirmation' => 'required|same:password',
            ]);

            $user = User::create([
                'login' => $request->input('login'),
                'email' => $request->input('email'),
                'password' => Hash::make($request->input('password'))
            ]);

            Auth::loginUsingId($user->id);
            return response()->json(['message' => 'Регистрация прошла успешно!']);
        }
        catch (ValidationException $e) {
            return response()->json([
                'error' => 'Ошибка ввода данных',
                'errors' => $e->errors()
            ], 422);
        }
            // Общая ошибка
        catch (\Exception $e) {
            return response()->json([
                'error' => 'Ошибка во время регистрации. Попробуйте еще раз'
            ], 401);
        }
    }
}
