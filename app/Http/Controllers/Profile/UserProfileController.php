<?php

namespace App\Http\Controllers\Profile;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
class UserProfileController extends Controller
{
    public function showProfileData() {
        $user = Auth::user();

        if($user) {
        return response()->json([
            'login' => $user->login,
            'email' => $user->email,
        ]);
        } else {
            return response()->json(['error' => 'Ошибка!']);
        }
    }

    public function updateProfileData(Request $request) {
        $user = Auth::user();

        if ($user) {
            $validatedData = $request->validate([
                'login' => 'required|string|max:255|unique:users,login,' . $user->id,
                'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            ]);

            // Проверка, изменились ли данные
            if ($user->login === $validatedData['login'] && $user->email === $validatedData['email']) {
                return response()->json(['message' => 'Данные профиля не изменились.'], 200);
            }

            // Проверка времени последнего обновления
            if ($user->updated_at && $user->updated_at->diffInMinutes(now()) < 10) {
                return response()->json(['message' => 'Вы можете обновить данные только раз в 10 минут.'], 429);
            }

            // Обновление данных пользователя
            $user->login = $validatedData['login'];
            $user->email = $validatedData['email'];
            $user->save();

            return response()->json(['message' => 'Данные профиля успешно обновлены.']);
        }

        return response()->json(['error' => 'Ошибка! Пользователь не найден.'], 404);
    }
}
