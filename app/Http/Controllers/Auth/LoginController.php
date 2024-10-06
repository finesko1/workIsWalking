<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    public function loginStore(Request $request) {
        try {
            $request->validate([
                'login' => 'required|string',
                'password' => 'required|string',
            ]);

            $login = $request->input('login');
            $pswd = $request->input('password');

            $user = User::where('login', $login)
                ->orWhere('email', $login)
                ->first();

            if ($user) {
                if (Hash::check($pswd, $user->password)) {
                    Auth::login($user);

                    $userId = $user->id;
                    $userFolder = "users/{$userId}";

                    // Определяем имя файла
                    $imageName = 'userImage'; // Имя файла без расширения

                    // Проверяем существование файлов с различными расширениями
                    $extensions = ['jpg', 'jpeg', 'png', 'gif']; // Массив возможных расширений
                    $imageUrl = null; // Изначально устанавливаем значение null

                    foreach ($extensions as $extension) {
                        $imagePath = "{$userFolder}/{$imageName}.{$extension}"; // Формируем полный путь к изображению

                        // Проверяем, существует ли файл
                        if (Storage::disk('public')->exists($imagePath)) {
                            $imageUrl = Storage::url($imagePath);
                            $imageUrl .= '?t=' . time();
                            break;
                        }
                    }

                    return response()->json([
                        'message' => 'Вход выполнен успешно!',
                        'user' => [
                            'login' => $user->login,
                            'email' => $user->email,
                            'image_url' => $imageUrl ?? null
                        ]
                    ], 200);
                } else {
                    return response()->json([
                        'error' => 'Вход не выполнен',
                        'errors' => [
                            'password' => ['Неправильный пароль']
                        ]
                    ], 422);  // 422 - validation
                }
            } else {
                return response()->json([
                    'error' => 'Вход не выполнен',
                    'errors' => [
                        'login' => ['Пользователь не найден']
                    ]
                ], 422);
            }
        }
        catch (ValidationException $e) {
            return response()->json([
                'error' => 'Ошибка ввода данных',
                'errors' => $e->errors()
            ], 422);
        }
        catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], 401); // HTTP код 401 для неавторизованного запроса
        }
    }
}
