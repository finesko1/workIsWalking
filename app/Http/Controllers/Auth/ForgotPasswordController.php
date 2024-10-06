<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
// работа с почтой
use App\Mail\ResetPasswordMail;
use Illuminate\Support\Facades\Mail;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\URL;

class ForgotPasswordController extends Controller
{
    // сообщение на mail
    public function sendResetLink(Request $request) {
        $request->validate([
            'email' => 'email|required|exists:users',
        ]);

        // Отправляем стандартный email с токеном
        $status = Password::sendResetLink(
            $request->only('email'),
            function ($user, $token) use ($request) {
                // Создаем URL с токеном и добавляем email в качестве параметра
                $resetUrl = URL::to('/reset-password/' . $token) . '?email=' . urlencode($request->email);

                // Добавляем routeName в качестве параметра
                //$resetUrlWithRouteName = $resetUrl . '&routeName=ResetPassword';

                // Отправляем email с ResetPasswordMail
                Mail::to($user->email)->send(new ResetPasswordMail($resetUrl));
            }
        );

        return $status === Password::RESET_LINK_SENT
            ? response()->json(['status' => __($status)])
            : response()->json(['email' => __($status)], 422);
    }


    // форма с изменением пароля
    public function index(Request $request, $token)
    {
        // Извлекаем email и routeName из запроса
        $email = $request->query('email');
        $routeName = $request->query('routeName');
        return view('welcome', compact('token', 'email', 'routeName'));
    }


    public function update(Request $request) {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password),
                ])->save();
            }
        );

        return $status === Password::PASSWORD_RESET
            ? response()->json(['status' => __($status)])
            : response()->json(['email' => __($status)], 422);
    }
}
