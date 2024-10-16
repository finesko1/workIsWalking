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
use Illuminate\Validation\ValidationException;

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

                Mail::to($user->email)->send(new ResetPasswordMail($resetUrl));
            }
        );

        return $status === Password::RESET_LINK_SENT
            //? response()->json(['message' => __($status)])
            //: response()->json(['error' => __($status)], 422);
            ? response()->json(['message' => 'Ссылка для восстановления пароля успешно отправлена вам на почту!'])
            : response()->json(['error' => 'Ошибка отправления ссылки для восстановления!']);
    }


    // форма с изменением пароля
    public function index(Request $request, $token)
    {
        $email = $request->query('email');
        return view('welcome', compact('token', 'email'));
    }


    public function update(Request $request) {
        try {
            $validation = $request->validate([
                'token' => 'required',
                'email' => 'required|email',
                'password' => 'required|string|min:8|confirmed',
                'password_confirmation' => 'required|string|min:8|same:password',
            ]);

            $status = Password::reset(
                $request->only($validation['email']),
                function ($user, $password) {
                    $user->forceFill([
                        'password' => Hash::make($password),
                    ])->save();
                }
            );

            return response()->json(['message' => 'Пароль успешно изменен!']);
        }
        catch (ValidationException $e) {
            return response()->json([
                'errors' => $e->errors(),
                'error' => 'Ошибка ввода данных'
            ], 422);
        }
    }
}
