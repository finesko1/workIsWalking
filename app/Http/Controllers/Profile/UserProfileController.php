<?php

namespace App\Http\Controllers\Profile;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use App\Models\User\PersonalData;
class UserProfileController extends Controller
{
    public function showProfileData() {
        $user = Auth::user();

        if ($user) {
            $userId = $user->id;
            $userFolder = "users/{$userId}";

            // Определяем имя файла
            $imageName = 'userImage'; // Имя файла без расширения

            // Проверяем существование файлов с различными расширениями
            $extensions = ['jpg', 'jpeg', 'png', 'gif']; // Массив возможных расширений

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

    public function updateProfileData(Request $request) {
        try {
            $user = Auth::user();

            if (!$user) {
                return response()->json(['error' => 'Ошибка! Пользователь не авторизован'], 401);
            }

            $validatedData = $request->validate([
                'login' => 'sometimes|required|string|max:255|unique:users,login,' . $user->id,
                'email' => 'sometimes|required|email|max:255|unique:users,email,' . $user->id,
                'password' => 'sometimes|nullable|string|min:8|confirmed',
                'password_confirmation' => 'sometimes|nullable|string|min:8|same:password',
                'image_file' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
            ]);

            // Проверка времени последнего обновления
            if ($user->updated_at && $user->updated_at->diffInMinutes(now()) < 5) {
                return response()->json(['error' => 'Вы можете обновить данные раз в 5 минут.'], 429);
            }

            // Работа с userImage
            if ($request->hasFile('image_file')) {
                $userId = auth()->id(); // Получаем ID текущего пользователя
                $userFolder = "users/{$userId}";

                // Создаем папку, если она не существует
                if (!Storage::disk('public')->exists($userFolder)) {
                    Storage::disk('public')->makeDirectory($userFolder);
                }

                // Определяем имя файла
                $fileName = 'userImage'; // Имя файла без расширения

                // Проверяем существование файлов с различными расширениями и удаляем их
                $extensions = ['jpg', 'jpeg', 'png', 'gif']; // Массив возможных расширений
                foreach ($extensions as $extension) {
                    $existingFilePath = "{$userFolder}/{$fileName}.{$extension}";

                    // Если файл существует, удаляем его
                    if (Storage::disk('public')->exists($existingFilePath)) {
                        Storage::disk('public')->delete($existingFilePath);
                    }
                }

                // Сохраняем новый файл с именем userImage и его расширением
                $extension = $request->file('image_file')->getClientOriginalExtension();
                try {
                    $request->file('image_file')->storeAs($userFolder, "{$fileName}.{$extension}", 'public');
                } catch (\Exception $e) {
                    return response()->json(['error' => 'Ошибка при сохранении файла: ' . $e->getMessage()], 500);
                }
            }

            // Проверка изменений
            $dataToUpdate = [];
            if ($user->login !== $validatedData['login']) {
                $dataToUpdate['login'] = $validatedData['login'];
            }
            if ($user->email !== $validatedData['email']) {
                $dataToUpdate['email'] = $validatedData['email'];
            }

            // Обновление пароля только если он был введен
            if (!empty($validatedData['password'])) {
                $dataToUpdate['password'] = Hash::make($validatedData['password']);
            }

            // Обновление данных пользователя
            if (!empty($dataToUpdate)) {
                $user->update($dataToUpdate);
            }

            return response()->json(['message' => 'Данные профиля успешно обновлены.']);
        } catch (ValidationException $e) {
            return response()->json([
                'error' => 'Ошибка ввода данных',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }


    // работа с личной информацией
    public function showPersonalData() {
        $user = Auth::user();

        if (!$user) {
            return response()->json(['message' => 'User not authenticated'], 401);
        }

        // Получаем личные данные пользователя
        $personalData = $user->personalData;

        // Если личные данные отсутствуют, создаем их с пустыми значениями
        if (!$personalData) {
            $personalData = $user->personalData()->create([
                'first_name' => '',
                'second_name' => '',
                'phone_number' => null,
                'city' => null,
            ]);
        }

        return response()->json(['personalData' => $personalData], 200);
    }

    public function updatePersonalData(Request $request) {
        $user = Auth::user();
        $personalData = PersonalData::findOrFail($user->id);
        $validatedData = $request->validate([
            'first_name' => 'required|string|regex:/^[А-ЯЁ][а-яё]+$/u|max:50',
            'second_name' => 'required|string|regex:/^[А-ЯЁ][а-яё]+$/u|max:50',
            'phone_number' => 'nullable|string|max:15|unique:personal_data,phone_number,' . $personalData->id,
            'city' => 'nullable|string|max:255',
        ]);

        // Используем updateOrCreate для обновления или создания записи
        $personalData->update($validatedData);

        return response()->json(['message' => 'Данные успешно сохранены!', 'personalData' => $personalData], 200);
    }
}
