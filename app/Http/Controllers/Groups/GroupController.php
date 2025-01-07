<?php

namespace App\Http\Controllers\Groups;

use App\Http\Controllers\Controller;
use App\Models\Group\Group;
use App\Models\Group\Material;
use App\Models\Group\Material_links;
use App\Models\Group\User_Groups;
use App\Models\User\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Mockery\Exception;

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

    // Получение данных группы
    public function getData($groupId) {
        try {
            // Аутентификация пользователя
            $user = auth()->user();
            if (!$user) {
                return response()->json(['error' => 'Пользователь не авторизован'], 401);
            }
            // Проверка существования группы
            Group::findOrFail($groupId);
            // Проверка прав пользователя (например, является ли он администратором группы)
            $userGroup = User_Groups::where('user_id', $user->id)
                ->where('group_id', $groupId)
                ->first();
            if (!$userGroup || $userGroup->role !== 'admin') {
                return response()->json(['error' => 'У вас нет прав для изменения этой группы'], 403);
            }

            $groupDirectory = "groups/{$groupId}/materials";

            // Получение всех папок в директории группы
            $directories = Storage::disk('public')->directories($groupDirectory);

            $materialSections = [];

            foreach ($directories as $directory) {
                // Получение всех файлов в текущей папке
                $files = Storage::disk('public')->files($directory);

                // Преобразование файлов в нужный формат
                $materials = array_map(function ($file) {
                    return [
                        'name' => basename($file),
                        'file' => Storage::disk('public')->url($file),
                        'isNew' => false,
                        'isDelete' => false,
                    ];
                }, $files);

                // Добавление информации о секции в массив
                $materialSections[] = [
                    'sectionName' => basename($directory),
                    'materials' => $materials,
                    'isNew' => false,
                    'isDelete' => false,
                ];
            }

            return response()->json([
                'message' => 'Данные успешно получены!',
                'materialSections' => $materialSections,
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Ошибка получения данных группы', 'errors' => $e->getMessage()], 500);
        }
    }

    // Получение материалов группы
    public function getMaterialData($groupId) {
        try {
            // Аутентификация пользователя
            $user = auth()->user();
            if (!$user) {
                return response()->json(['error' => 'Пользователь не авторизован'], 401);
            }
            // Проверка существования группы
            Group::findOrFail($groupId);
            // Проверка прав пользователя (например, является ли он администратором группы)
            $userGroup = User_Groups::where('user_id', $user->id)
                ->where('group_id', $groupId)
                ->first();
            //if (!$userGroup || $userGroup->role !== 'admin') {
            //    return response()->json(['error' => 'У вас нет прав для изменения этой группы'], 403);
            //}

            $groupDirectory = "groups/{$groupId}/materials";

            // Получение всех папок в директории материалов группы
            $directories = Storage::disk('public')->directories($groupDirectory);

            $materialSections = [];

            foreach ($directories as $directory) {
                // Получение всех файлов в текущей папке
                $files = Storage::disk('public')->files($directory);

                // Преобразование файлов в нужный формат
                $materials = array_map(function ($file) {
                    return [
                        'name' => basename($file),
                        'file' => Storage::disk('public')->url($file)
                    ];
                }, $files);

                // Добавление информации о секции в массив
                $materialSections[] = [
                    'sectionName' => basename($directory),
                    'materials' => $materials,
                ];
            }

            return response()->json([
                'message' => 'Данные успешно получены!',
                'materialSections' => $materialSections,
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Ошибка получения данных группы', 'errors' => $e->getMessage()], 500);
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
            return response()->json(['error' => 'Ошибка при предпросмотре файла'], 500);
        }
    }

    // Активация сообщений группы
    public function checkChat($groupId) {
        try {
            if (!request()->expectsJson()) {
                return view('welcome');
            }
            Group::findOrFail($groupId);

            $chatIsOpen = Group::where('id', $groupId)->value('is_chat_open');

            return response()->json(['chatIsOpen' => $chatIsOpen]);
        } catch (Exception $e) {
            return response()->json(['error' => 'Ошибка при анализе чата', 'errors' => $e->getMessage()], 500);
        }
    }

    // Сохранение изменений материалов группы
    public function saveChanges(Request $request, $groupId) {
        try {
            // Аутентификация пользователя
            $user = auth()->user();
            if (!$user) {
                return response()->json(['error' => 'Пользователь не авторизован'], 401);
            }
            // Проверка существования группы
            Group::findOrFail($groupId);
            // Проверка прав пользователя (например, является ли он администратором группы)
            $userGroup = User_Groups::where('user_id', $user->id)
                ->where('group_id', $groupId)
                ->first();
            if (!$userGroup || $userGroup->role !== 'admin') {
                return response()->json(['error' => 'У вас нет прав для изменения этой группы'], 403);
            }

            $materialSections = $request->input('materialSections');

            // Валидация входящих данных
            $request->validate([
                'materialSections' => 'required|array',
                'materialSections.*.sectionName' => 'required|string|max:255',
                'materialSections.*.isNew' => 'required|boolean',
                'materialSections.*.isDelete' => 'required|boolean',
                'materialSections.*.materials' => 'required|array',
                'materialSections.*.materials.*.materialName' => 'required|string|max:255',
                'materialSections.*.materials.*.isNew' => 'required|boolean',
                'materialSections.*.materials.*.isDelete' => 'required|boolean',
                'materialSections.*.materials.*.file' => 'nullable|file|mimes:jpg,jpeg,png,pdf,doc,docx|max:53248',
            ]);

            // Анализ разделов
            foreach ($materialSections as $materialSectionIndex => $materialSection) {
                // Декодирование JSON строки для sectionName
                $decodedSectionName = json_decode($materialSection['sectionName'], true);
                if (json_last_error() !== JSON_ERROR_NONE) {
                    $decodedSectionName = $materialSection['sectionName'];
                }

                // Если раздел не нужно удалять
                if (!$materialSection['isDelete']) {
                    // Если раздел необходимо создать
                    if ($materialSection['isNew']) {
                        // Каталог материалов группы
                        $newDirectory = 'groups/' . $groupId . '/materials/' . $decodedSectionName;
                        if (!Storage::disk('public')->exists($newDirectory)) {
                            Storage::disk('public')->makeDirectory($newDirectory);
                            // Создание каталога в базе данных
                            Material::create([
                                'group_id' => $groupId,
                                'section' => $decodedSectionName,
                            ]);
                        }
                    }

                    // Работа с материалами группы
                    foreach ($materialSection['materials'] as $materialIndex => $material) {
                        // Декодирование JSON строки для materialName
                        $decodedMaterialName = json_decode($material['materialName'], true);
                        if (json_last_error() !== JSON_ERROR_NONE) {
                            $decodedMaterialName = $material['materialName']; // Используйте оригинальное значение, если декодирование не удалось
                        }

                        $currMaterial = 'groups/' . $groupId . '/materials/' . $decodedSectionName . '/' .  $decodedMaterialName;
                        if (!$material['isDelete']) {
                            if ($material['isNew']) {
                                // Проверка на существование файла в хранилище + проверка на наличие файла в массиве данных
                                if (!Storage::disk('public')->exists($currMaterial) && $request->hasFile("materialSections.{$materialSectionIndex}.materials.{$materialIndex}.file")) {
                                    // Получение файла
                                    $file = $request->file("materialSections.{$materialSectionIndex}.materials.{$materialIndex}.file");

                                    // Добавление файла в хранилище
                                    $directoryPath = "groups/{$groupId}/materials/{$decodedSectionName}/";
                                    $file->storeAs($directoryPath, $file->getClientOriginalName(), 'public');

                                    // Создание записи о файле в БД
                                    $materialId = Material::where('section', $decodedSectionName)->first();
                                    if ($materialId) {
                                        Material_links::create([
                                            'material_id' => $materialId->id,
                                            'filename' => $file->getClientOriginalName(),
                                        ]);
                                    }
                                }
                            }
                        } else {
                            // Удаление материала в каталоге
                            if (Storage::disk('public')->exists($currMaterial)) {
                                Storage::disk('public')->delete($currMaterial);
                                // Удаление материала из базы данных
                                Material_links::where('filename', $decodedMaterialName)->first()->delete();
                            }
                        }
                    }
                    // Проверка, остались ли файлы в каталоге после удаления материалов
                    $remainingFiles = Storage::disk('public')->files("groups/{$groupId}/materials/{$decodedSectionName}");
                    if (empty($remainingFiles)) {
                        // Если файлов не осталось, удаляем каталог
                        Storage::disk('public')->deleteDirectory("groups/{$groupId}/materials/{$decodedSectionName}");
                        // Удаление записи в БД
                        Material::where('section', $decodedSectionName)
                            ->where('group_id', $groupId)
                            ->first()->delete();
                    }
                } else {
                    // Удаление каталога
                    $materialSectionPath = '/groups/' . $groupId . '/materials/' . $decodedSectionName;
                    if (Storage::disk('public')->exists($materialSectionPath)) {
                        Storage::disk('public')->deleteDirectory($materialSectionPath);
                        // Удаление записи в БД
                        Material::where('section', $decodedSectionName)->
                        where('group_id', $groupId)->
                        first()->delete();
                    }
                }
            }
            return response()->json(['message' => 'Данные обновлены']);
        } catch (ValidationException $e) {
            return response()->json(['error' => 'Произошла ошибка при валидации данных', 'errors' => $e->getMessage()], 422);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Произошла ошибка при обработке данных', 'errors' => $e->getMessage()], 500);
        }
    }

    // Получение роли пользователя в группе
    public function checkRole($groupId) {
        try {
            Group::findOrFail($groupId);
            $user = auth()->user();
            $userRole = User_Groups::where('group_id', $groupId)
                ->where('user_id', $user->id)->first()->role;
            return response()->json(['message' => "Роль пользователя получена",
                'role' => $userRole]);
        } catch (Exception $e) {
            return response()->json(['error' => 'Ошибка при получении роли пользователя',
                'errors' => $e->getMessage()], 500);
        }
    }

    // Получение пользователей-друзей, доступных для добавления в группу
    public function getPreferredFriends($groupId) {
        try {
            $user = auth()->user();
            Group::findOrFail($groupId);
            $friends = $user->friends()->whereNull('friendships.deleted_at')->get();
            // скрытие промежуточных данных
            $user->friends->makeHidden('pivot');
            $friendsData = $friends->map(function ($user) use ($groupId) {
                // Если человек состоит в группе, пропускаем
                if (User_Groups::where('group_id', $groupId)
                    ->where('user_id', $user->id)->exists()) {
                    return;
                }
                return $this->getUsersData($user);
            })->filter(); // Удаляем null значения из коллекции

            // Если запрос ожидает JSON (например, через Axios)
            if (request()->expectsJson()) {
                return response()->json([
                    'message' => 'all successfully',
                    'preferredFriendsData' => $friendsData,
                ], 200);
            }

            // Если запрос был сделан через URL в браузере
            return view('welcome', [
                'message' => 'all successfully',
                'preferredFriendsData' => $friendsData,
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    // Добавление пользователя в группу
    public function addPreferredFriends(Request $request, $groupId) {
        try {
            Group::findOrFail($groupId);
            $user = auth()->user();
            if (!$user) {
                return response()->json(['error' => 'Вы не авторизованы']);
            }

            $userRole = User_Groups::where('group_id', $groupId)
                ->where('user_id', $user->id)->first()->role;

            if ($userRole == 'admin' || $userRole == 'manager') {
                User_Groups::create([
                    'group_id' => $groupId,
                    'user_id' => $request->input('preferredUserId'),
                    'role' => 'user',
                ]);
            } else {
                return response()->json(['error' => 'Недостаточно прав']);
            }

            return response()->json(['message' => 'Пользователь добавлен в группу']);
        } catch (Exception $e) {
            return response()->json(['error' => 'Ошибка при добавлении пользователя в группу',
                'errors' => $e->getMessage()], 500);
        }
    }


    // Служебные функции: получение изображения профиля + данные пользователя
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

    public function countUsers($groupId) {
        try {
            $user = auth()->user();
            if (!$user) {
                return response()->json(['error' => 'Ошибка авторизации'], 401);
            }

            // Получаем количество участников
            $countUsersInGroup = User_Groups::where('group_id', $groupId)->count();


            if(request()->expectsJson()) {
                return response()->json(['message' => 'Запрос выполнен успешно',
                    'countUsers' => $countUsersInGroup
                ], 200);
            }
            return view('welcome', ['countUsers' => $countUsersInGroup]);

        }
        catch (ValidationException $e) {
            return response()->json(['error' => 'Ошибка валидации',
                'errors' => $e->errors()]);
        }
        catch (\Exception $e) {
            if(request()->expectsJson()) {
                return response()->json(['error' => 'Попробуйте перезагрузить страницу'
                ], 500);
            }
            return view('welcome');
        }
    }
}
