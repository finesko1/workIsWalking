<?php

namespace App\Http\Controllers\Groups;

use App\Http\Controllers\Controller;
use App\Models\Group\Group;
use App\Models\Group\Material;
use App\Models\Group\User_Groups;
use App\Models\User\User;
use Illuminate\Support\Facades\Storage;

class GroupController extends Controller
{
    // Получение данных (профилей) пользователей
    public function users($groupId) {
        try {
            $user = auth()->user();
            if (!$user) {
                return response()->json(['error' => 'Ошибка авторизации'], 401);
            }


            // Получаем информацию об участниках
            $usersInGroup = User_Groups::where('group_id', $groupId)->get();

            $usersData = $usersInGroup->map(function ($userGroup) {
                $user = User::find($userGroup->user_id);
                return $this->getUsersData($user);
            });

            if (request()->expectsJson()) {
                return response()->json(['message' => 'Запрос выполнен успешно',
                    'usersData' => $usersData
                ], 200);
            }
            return view('welcome', ['usersData' => $usersData]);

        } catch (\Exception $e) {
            if (request()->expectsJson()) {
                return response()->json(['error' => 'Попробуйте перезагрузить страницу'], 500);
            }
            return view('welcome');
        }
    }

    // Получение материалов группы
    public function getMaterials($groupId) {
        try {

            if(!request()->expectsJson()) {
                return view('welcome');
            }

            $materials = Material::where('group_id', $groupId)
                ->where('info', 'like', '/materials/%')
                ->get();
            $material_links = $materials->map(function ($material) {
                return $material->links;
            });
            $materialsPath = $materials->map(function ($material) {
                return $material->info;
            });

            $materialDirectoryNames = $materials->map(function ($material) {
                return basename($material->info);
            });

            return response()->json(['message' => 'Материалы группы успешно получены',
                'directories' => $materialDirectoryNames,
                'links' => $material_links,
                'paths' => $materialsPath]);
        } catch (\Exception $e) {
            if (request()->expectsJson()) {
                return response()->json(['error' => $e->getMessage()], 500);
            } else {
                return view('welcome');
            }
        }

    }


    // Получение заданий группы
    public function getTasks($groupId) {
        try {

            if(!request()->expectsJson()) {
                return view('welcome');
            }

            $tasks = Material::where('group_id', $groupId)
                ->where('info', 'regexp', '^/tasks/[^/]+$')
                ->get();
            $task_links = $tasks->map(function ($task) {
                return $task->links;
            });

            $taskDirectoryNames = $tasks->map(function ($task) {
                return basename($task->info);
            });

            return response()->json(['message' => 'Задания группы успешно получены',
                'directories' => $taskDirectoryNames,
                'links' => $task_links]);
        } catch (\Exception $e) {
            if (request()->expectsJson()) {
                return response()->json(['error' => $e->getMessage()], 500);
            } else {
                return view('welcome');
            }
        }

    }


    // Просмотр файла на сервере
    public function previewFile($groupId, $filePath)
    {
        try {
            $filePath = "/groups/{$groupId}/{$filePath}";

            // Получение содержимого файла и его MIME-типа
            $fileContent = Storage::disk('public')->get($filePath);
            $mimeType = Storage::disk('public')->mimeType($filePath);

            return response($fileContent, 200)->header('Content-Type', $mimeType);
        } catch (\Exception $e) {
            // Логирование ошибки для отладки
            \Log::error('Ошибка при предпросмотре файла: ' . $e->getMessage());
            return response()->json(['error' => 'Ошибка при предпросмотре файла'], 500);
        }
    }


    public function checkChat($groupId) {
        if (!request()->expectsJson()) {
            return view('welcome');
        }
        $chatIsOpen = Group::where('id', $groupId)->value('is_chat_open');

        return response()->json(['chatIsOpen' => $chatIsOpen]);
    }


    private function getUrl($user): string
    {
        $userId = $user->id;
        $userFolder = "users/{$userId}";
        $imageName = "userImage";
        $extensions = ['png', 'jpg', 'jpeg'];

        foreach ($extensions as $extension) {
            $imagePath = "{$userFolder}/{$imageName}.{$extension}";
            if (Storage::disk('public')->exists($imagePath)) {
                return Storage::url($imagePath) . '?t=' . time();
            }
        }
        return '';
    }

    /**
     * @param User $user
     * @return array
     */
    private function getUsersData($user) {
        $imageUrl = $this->getUrl($user);
        if ($user->personalData && $user->personalData->first_name && $user->personalData->second_name) {
            return [
                'id' => $user->id,
                'first_name' => $user->personalData->first_name,
                'second_name' => $user->personalData->second_name,
                'image_url' => $imageUrl ? $imageUrl : null,
            ];
        } else {
            // ссылка на фото профиля
            $imageUrl = $this->getUrl($user);
            return [
                'id' => $user->id,
                'login' => $user->login,
                'image_url' => $imageUrl ? $imageUrl : null,
            ];
        }
    }
}
