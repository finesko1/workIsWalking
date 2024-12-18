<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class CheckAuthController extends Controller
{
    public function checkAuth(Request $request) {
        $user = auth()->user();

        if ($user) {
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
                'message' => 'Вы авторизованы!',
                'user' => [
                    'login' => $user->login,
                    'email' => $user->email,
                    'image_url' => $imageUrl ?? null
                ]
            ], 200);
        } else {
            return response()->json(['error' => 'Ошибка авторизации'], 401);
        }
    }
}
