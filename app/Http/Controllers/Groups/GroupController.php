<?php

namespace App\Http\Controllers\Groups;

use App\Http\Controllers\Controller;
use App\Models\Group\Group;
use App\Models\Group\Material;
use App\Models\Group\Material_links;
use App\Models\Group\TaskMaterialDeadline;
use App\Models\Group\User_Groups;
use App\Models\Group\UserSolution;
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
            // Количество участников
            $countUsersInGroup = User_Groups::where('group_id', $groupId)->count();

            $usersData = $usersInGroup->map(function ($userGroup) {
                $user = User::find($userGroup->user_id);
                return $this->getUsersData($user);
            });

            if (request()->expectsJson()) {
                return response()->json(['message' => 'Запрос выполнен успешно',
                    'usersData' => $usersData,
                    'countUsers' => $countUsersInGroup
                ], 200);
            }
            return view('welcome', [
                'usersData' => $usersData,
                'countUsers' => $countUsersInGroup
            ]);

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
//            if ($this->getUserRole(user->id, $groupId) !== 'admin') {
//                return response()->json(['error' => 'У вас нет прав для изменения этой группы'], 403);
//            }

            $groupDirectory = "groups/{$groupId}/materials";
            $tasksDirectory = "groups/{$groupId}/tasks";
            // Получение всех папок в директории группы
            $directories = Storage::disk('public')->directories($groupDirectory);
            $taskDirectories = Storage::disk('public')->directories($tasksDirectory);
            $materialSections = [];
            $taskSections = [];
            // Получение материалов группы
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
            // Получение заданий группы
            foreach ($taskDirectories as $directory) {
                $files = Storage::disk('public')->files($directory);
                $materials = array_map(function ($file) use ($user) {
                    $materialLink = Material_links::where('filename', basename($file))->first();
                    if (!$materialLink) {
                        return null;
                    }

                    $deadlineEntry = $materialLink->deadlines->where('user_id', $user->id)->first();
                    $deadline = $deadlineEntry ? $deadlineEntry->deadline : null;

                    return [
                        'name' => basename($file),
                        'file' => Storage::disk('public')->url($file),
                        'isNew' => false,
                        'isDelete' => false,
                        'access_users' => $materialLink->access_users,
                        'deadline' => $deadline,
                    ];
                }, $files);

                $materials = array_filter($materials);

                if (!empty($materials)) {
                    $taskSections[] = [
                        'sectionName' => basename($directory),
                        'materials' => $materials,
                        'isNew' => false,
                        'isDelete' => false,
                    ];
                }
            }

            // Получение данных пользователей группы
            $usersResponse = $this->users($groupId);
            $usersData = $usersResponse->getData(true);
            foreach ($usersData['usersData'] as &$user) {
                $user['role'] = $this->getUserRole($user['id'], $groupId);
            }

            return response()->json([
                'message' => 'Данные успешно получены!',
                'materialSections' => $materialSections,
                'taskSections' => $taskSections,
                'usersData' => $usersData['usersData'] ?? [],
                'countUsers' => $usersData['countUsers'] ?? 0,
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

    // Получение заданий группы
    public function getTaskData($groupId)
    {
        try {
            $user = auth()->user();
            if (!$user) {
                return response()->json(['error' => 'Пользователь не авторизован'], 401);
            }

            // Проверка существования группы
            Group::findOrFail($groupId);

            // Определение роли пользователя в группе
            $userRole = $this->getUserRole($user->id, $groupId);

            $tasksDirectory = "groups/{$groupId}/tasks";
            $taskDirectories = Storage::disk('public')->directories($tasksDirectory);
            $taskSections = [];

            foreach ($taskDirectories as $directory) {
                // Получение всех файлов в текущей папке
                $files = Storage::disk('public')->files($directory);

                // Фильтрация и преобразование файлов на основе прав доступа
                $materials = array_map(function ($file) use ($user, $userRole) {
                    $materialLink = Material_links::where('filename', basename($file))->first();

                    if (!$materialLink) {
                        return null;
                    }

                    // Если пользователь имеет роль админа, предоставляем доступ ко всем заданиям
                    if ($userRole === 'admin') {
                        $deadlineEntry = $materialLink->deadlines->where('user_id', $user->id)->first();
                        $deadline = $deadlineEntry ? $deadlineEntry->deadline : null;
                        $solutionSubmitted = UserSolution::where('user_id', $user->id)
                            ->where('task_material_deadline_id', $deadlineEntry->id ?? null)
                            ->exists();
                        return [
                            'name' => basename($file),
                            'file' => Storage::disk('public')->url($file),
                            'isNew' => false,
                            'isDelete' => false,
                            'access_users' => $materialLink->access_users,
                            'deadline' => $deadline,
                            'solutionSubmitted' => $solutionSubmitted,
                        ];
                    }

                    // Иначе проверяем доступ по полю access_users
                    $accessUsers = $materialLink->access_users;

                    if ($accessUsers === 'all') {
                        // Получение наиболее позднего срока из всех записей
                        $deadline = $materialLink->deadlines->pluck('deadline')->max();

                        return [
                            'name' => basename($file),
                            'file' => Storage::disk('public')->url($file),
                            'isNew' => false,
                            'isDelete' => false,
                            'access_users' => $accessUsers,
                            'deadline' => $deadline,
                        ];
                    }

                    // Предполагается, что access_users хранит ID пользователей через запятую
                    $allowedUsers = array_map('trim', explode(',', $accessUsers));
                    if (in_array($user->id, $allowedUsers)) {
                        $deadlineEntry = $materialLink->deadlines->where('user_id', $user->id)->first();
                        $deadline = $deadlineEntry ? $deadlineEntry->deadline : null;

                        return [
                            'name' => basename($file),
                            'file' => Storage::disk('public')->url($file),
                            'isNew' => false,
                            'isDelete' => false,
                            'access_users' => $accessUsers,
                            'deadline' => $deadline,
                        ];
                    }

                    return null;
                }, $files);

                // Удаление заданий, к которым пользователь не имеет доступа
                $materials = array_filter($materials);

                if (!empty($materials)) {
                    $taskSections[] = [
                        'sectionName' => basename($directory),
                        'materials' => $materials,
                        'isNew' => false,
                        'isDelete' => false,
                    ];
                }
            }

            return response()->json([
                'message' => 'Данные заданий получены успешно',
                'taskSections' => $taskSections,
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Ошибка при получении данных заданий', 'errors' => $e->getMessage()], 500);
        }
    }

    public function getAccessUsers($groupId, $sectionName, $taskName) {
        try {
            $user = auth()->user();
            if (!$user) {
                return response()->json(['error' => 'Пользователь не авторизован'], 401);
            }

            // Проверка существования группы
            Group::findOrFail($groupId);

            // Определение роли пользователя в группе
            $userRole = $this->getUserRole($user->id, $groupId);
            if ($userRole !== 'admin') {
                return response()->json(['error' => 'Недостаточно прав']);
            }

            $taskSection = Material::where('section', $sectionName)->first();
            if (!$taskSection) {
                return response()->json(['error' => "Не существует задания $sectionName"], 200, [], JSON_UNESCAPED_UNICODE);
            }
            $materialIds = Material_links::where('material_id', $taskSection->id)->pluck('id');

            $accessUsers = $materialIds->map(function($materialId) use ($groupId, $sectionName) {
                $taskMaterialDeadlines = TaskMaterialDeadline::where('material_link_id', $materialId)->get();
                $taskDirectory = 'groups/' . $groupId . '/tasks/' . $sectionName . '/answers/';

                return $taskMaterialDeadlines->map(function ($deadline) use ($taskDirectory) {
                    $userSolutions = UserSolution::where('task_material_deadline_id', $deadline->id)->get();
                    $user = User::find($deadline->user_id);
                    $userData = $this->getUsersData($user);

                    $files = $userSolutions->map(function ($solution) use ($taskDirectory) {
                        $filePath = $taskDirectory . $solution->user_id . '/' . $solution->solution_file;
                        return Storage::disk('public')->exists($filePath) ? Storage::disk('public')->url($filePath) : null;
                    })->filter();

                    return [
                        'userData' => $userData,
                        'files' => $files,
                    ];
                });
            });

            return response()->json(['message' => 'Запрос выполнен успешно', 'usersData' => $accessUsers], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Ошибка при получении данных заданий', 'errors' => $e->getMessage()], 500);
        }
    }

    // решение пользователя
    public function saveUserSolution(Request $request, $groupId, $sectionName)
    {
        $request->validate([
            'solution_files.*' => 'required|file|mimes:pdf,doc,docx',
        ]);

        $taskSection = Material::where('section', $sectionName)->first();
        if (!$taskSection) {
            return response()->json(['error' => "Не существует задания $sectionName"], 200, [], JSON_UNESCAPED_UNICODE);
        }
        $materialId = Material_links::where('material_id', $taskSection->id)->first()->id;
        $userId = auth()->user()->id;

        $taskMaterialDeadline = TaskMaterialDeadline::where('material_link_id', $materialId)
            ->where('user_id', $userId)
            ->first();
        if (!$taskMaterialDeadline) {
            return response()->json(['error' => 'Срок для этого задания не найден, либо у вас нет к нему доступа'], 404, [], JSON_UNESCAPED_UNICODE);
        }

        if ($request->hasFile('solution_files')) {
            $directoryPath = "groups/{$groupId}/tasks/{$sectionName}/answers/{$userId}";

            // Удаление существующих файлов в каталоге
            Storage::disk('public')->deleteDirectory($directoryPath);

            // Удаление существующих записей решения пользователя
            UserSolution::where('user_id', $userId)
                ->where('task_material_deadline_id', $taskMaterialDeadline->id)
                ->delete();

            $files = $request->file('solution_files');
            foreach ($files as $file) {
                // Сохранение нового файла
                $filePath = $file->storeAs($directoryPath, $file->getClientOriginalName(), 'public');

                // Сохранение информации о новом решении в БД
                $solution = new UserSolution();
                $solution->user_id = $userId;
                $solution->task_material_deadline_id = $taskMaterialDeadline->id;
                $solution->solution_file = basename($filePath);
                $solution->save();
            }

            return response()->json(['message' => 'Решения успешно обновлены'], 200, [], JSON_UNESCAPED_UNICODE);
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
            $taskSections = $request->input('taskSections');

            // Условная валидация
            $rules = [];
            if (!empty($materialSections)) {
                $rules['materialSections'] = 'array';
                $rules['materialSections.*.sectionName'] = 'required|string';
                $rules['materialSections.*.materials'] = 'array';
                $rules['materialSections.*.materials.*.materialName'] = 'required|string';
                $rules['materialSections.*.materials.*.isNew'] = 'required|boolean';
                $rules['materialSections.*.materials.*.isDelete'] = 'required|boolean';
            }

            if (!empty($taskSections)) {
                $rules['taskSections'] = 'array';
                $rules['taskSections.*.sectionName'] = 'required|string';
                $rules['taskSections.*.materials'] = 'array';
                $rules['taskSections.*.materials.*.materialName'] = 'required|string';
                $rules['taskSections.*.materials.*.isNew'] = 'required|boolean';
                $rules['taskSections.*.materials.*.isDelete'] = 'required|boolean';
                $rules['taskSections.*.materials.*.access_users'] = 'nullable|string';
            }

            // Выполнение валидации только если есть правила
            if (!empty($rules)) {
                $request->validate($rules);
            }

            if (!empty($materialSections))
            {
                // Анализ разделов материалов
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

                            $currMaterial = 'groups/' . $groupId . '/materials/' . $decodedSectionName . '/' . $decodedMaterialName;
                            if (!$material['isDelete']) {
                                if ($material['isNew']) {
                                    // Проверка на существование файла в хранилище + проверка на наличие файла в массиве данных
                                    if (!Storage::disk('public')->exists($currMaterial) && $request->hasFile("materialSections.{$materialSectionIndex}.materials.{$materialIndex}.file")) {
                                        // Получение файла
                                        $file = $request->file("materialSections.{$materialSectionIndex}.materials.{$materialIndex}.file");

                                        // Добавление файла в хранилище
                                        $directoryPath = "groups/{$groupId}/materials/{$decodedSectionName}/";
                                        $file->storeAs($directoryPath, $decodedMaterialName, 'public');

                                        // Создание записи о файле в БД
                                        $sectionId = Material::where('section', $decodedMaterialName)->first();
                                        if ($sectionId) {
                                            Material_links::create([
                                                'material_id' => $sectionId->id,
                                                'filename' => $decodedMaterialName,
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
                        $materialSectionPath = 'groups/' . $groupId . '/materials/' . $decodedSectionName;
                        if (Storage::disk('public')->exists($materialSectionPath)) {
                            Storage::disk('public')->deleteDirectory($materialSectionPath);
                            // Удаление записи в БД
                            Material::where('section', $decodedSectionName)->
                            where('group_id', $groupId)->
                            first()->delete();
                        }
                    }
                }
            }
            if (!empty($taskSections)) {
                // Анализ разделов заданий
                foreach ($taskSections as $taskSectionIndex => $taskSection) {
                    // Декодирование JSON строки для sectionName
                    $decodedSectionName = json_decode($taskSection['sectionName'], true);
                    if (json_last_error() !== JSON_ERROR_NONE) {
                        $decodedSectionName = $taskSection['sectionName'];
                    }

                    // Если раздел не нужно удалять
                    if (!$taskSection['isDelete']) {
                        // Если раздел необходимо создать
                        if ($taskSection['isNew']) {
                            // Каталог материалов группы
                            $newDirectory = 'groups/' . $groupId . '/tasks/' . $decodedSectionName;
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
                        foreach ($taskSection['materials'] as $materialIndex => $material) {
                            // Декодирование JSON строки для materialName
                            $decodedMaterialName = json_decode($material['materialName'], true);
                            if (json_last_error() !== JSON_ERROR_NONE) {
                                $decodedMaterialName = $material['materialName']; // Используйте оригинальное значение, если декодирование не удалось
                            }

                            $currMaterial = 'groups/' . $groupId . '/tasks/' . $decodedSectionName . '/' . $decodedMaterialName;
                            if (!$material['isDelete']) {
                                if ($material['isNew']) {
                                    // Проверка на существование файла в хранилище + проверка на наличие файла в массиве данных
                                    if (!Storage::disk('public')->exists($currMaterial) && $request->hasFile("taskSections.{$taskSectionIndex}.materials.{$materialIndex}.file")) {
                                        // Получение файла
                                        $file = $request->file("taskSections.{$taskSectionIndex}.materials.{$materialIndex}.file");

                                        // Добавление файла в хранилище
                                        $directoryPath = "groups/{$groupId}/tasks/{$decodedSectionName}/";
                                        $file->storeAs($directoryPath, $decodedMaterialName, 'public');

                                        // Создание записи о файле в БД
                                        $materialId = Material::where('section', $decodedSectionName)->first();
                                        if ($materialId) {
                                            Material_links::create([
                                                'material_id' => $materialId->id,
                                                'filename' => $decodedMaterialName,
                                                'access_users' => $material['access_users'] === 'all' ? 'all' : $material['access_users'],
                                            ]);
                                        }
                                    }
                                } else {
                                    $materialLink = Material_links::where('filename', $decodedMaterialName)->first();
                                    if ($materialLink) {
                                        $materialLink->update([
                                            'access_users' => $material['access_users'] === 'all' ? 'all' : $material['access_users'],
                                        ]);
                                    }
                                }

                                $deadline = $material['deadline'] == 'null' ? NULL : $material['deadline'];
                                $materialLink = Material_links::where('filename', $decodedMaterialName)->first();
                                if ($materialLink) {
                                    if($material['access_users'] == 'all'){
                                        $groupUsers = User_Groups::where('group_id', $groupId)->get();
                                        foreach ($groupUsers as $groupUser) {
                                            TaskMaterialDeadline::updateOrCreate(
                                                [
                                                    'material_link_id' => $materialLink->id,
                                                    'user_id' => $groupUser->id,
                                                ],
                                                [
                                                    'deadline' => $deadline,
                                                ]
                                            );
                                        }
                                    } else {
                                        TaskMaterialDeadline::where('material_link_id', $materialLink->id)->delete();
                                        // Разбиваем строку в массив строк
                                        $groupUsers = explode(',', $material['access_users']);
                                        foreach ($groupUsers as $groupUser) {
                                            TaskMaterialDeadline::updateOrCreate(
                                                [
                                                    'material_link_id' => $materialLink->id,
                                                    'user_id' => $groupUser,
                                                ],
                                                [
                                                    'deadline' => $deadline,
                                                ]
                                            );
                                        }
                                    }
                                } else {
                                    return response()->json(['error' => 'Ошибка создания заданий']);
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
                        $remainingFiles = Storage::disk('public')->files("groups/{$groupId}/tasks/{$decodedSectionName}");
                        if (empty($remainingFiles)) {
                            // Если файлов не осталось, удаляем каталог
                            Storage::disk('public')->deleteDirectory("groups/{$groupId}/tasks/{$decodedSectionName}");
                            // Удаление записи в БД
                            Material::where('section', $decodedSectionName)
                                ->where('group_id', $groupId)
                                ->first()->delete();
                        }
                    } else {
                        // Удаление каталога
                        $materialSectionPath = 'groups/' . $groupId . '/tasks/' . $decodedSectionName;
                        if (Storage::disk('public')->exists($materialSectionPath)) {
                            Storage::disk('public')->deleteDirectory($materialSectionPath);
                            // Удаление записи в БД
                            Material::where('section', $decodedSectionName)->
                            where('group_id', $groupId)->
                            first()->delete();
                        }
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
            $userRole = $this->getUserRole(auth()->user()->id, $groupId);
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

    // Удаление пользователя из группы
    public function kickUser($groupId, $userId) {
        try {
            $currentUserId = auth()->user()->id;
            $userRole = $this->getUserRole($currentUserId, $groupId);

            // Проверка существования группы
            $groupExists = $this->groupIsExists($groupId);
            if (!$groupExists) {
                return response()->json(['error' => 'Группы не существует']);
            }

            // Если это выход из группы
            if ($currentUserId == $userId) {
                if ($userRole == 'admin') {
                    // Проверка наличия других администраторов
                    $otherAdmins = User_Groups::where('group_id', $groupId)
                        ->where('role', 'admin')
                        ->where('user_id', '!=', $currentUserId)
                        ->exists();

                    if (!$otherAdmins) {
                        // Назначение нового администратора
                        $newAdmin = User_Groups::where('group_id', $groupId)
                            ->where('user_id', '!=', $currentUserId)
                            ->first();

                        if ($newAdmin) {
                            $newAdmin->role = 'admin';
                            $newAdmin->save();
                        } else {
                            return response()->json(['error' => 'Невозможно выйти, так как нет других участников для назначения администратора']);
                        }
                    }
                }

                // Удаление текущего пользователя из группы
                User_Groups::where('group_id', $groupId)
                    ->where('user_id', $currentUserId)->first()->delete();

                return response()->json(['message' => 'Вы успешно покинули группу']);
            }
            // Если это исключение участника из группы
            else {
                if ($userRole == 'admin') {
                    User_Groups::where('group_id', $groupId)
                        ->where('user_id', $userId)->first()->delete();
                    return response()->json(['message' => 'Пользователь успешно удален из группы']);
                } else {
                    return response()->json(['error' => 'Недостаточно прав для удаления пользователя']);
                }
            }
        } catch (\Exception $e) {
            return response()->json(['error' => 'Ошибка удаления пользователя из группы', 'errors' => $e->getMessage()], 500);
        }
    }

    // Обновление роли пользователя
    public function roleUpdate($groupId, $userId, Request $request) {
        try {
            $groupExists = $this->groupIsExists($groupId);
            if (!$groupExists) {
                return response()->json(['error' => 'Группы не существует']);
            }

            $myRole = $this->getUserRole(auth()->user()->id, $groupId);
            if ($myRole !== 'admin') {
                return response()->json(['error' => 'Недостаточно прав']);
            }

            if (auth()->user()->id == $userId) {
                return response()->json(['error' => 'Вы не можете изменить свою собственную роль'], 403);
            }

            // Validate the new role
            $request->validate([
                'newRole' => 'required|string|in:admin,moderator,user',
            ]);

            // Retrieve the user group record
            $userGroup = User_Groups::where('group_id', $groupId)
                ->where('user_id', $userId)
                ->first();

            if (!$userGroup) {
                return response()->json(['error' => 'Пользователь не найден в группе'], 404);
            }

            $firstAdmin = User_Groups::where('group_id', $groupId)
                ->where('role', 'admin')
                ->orderBy('created_at', 'asc')
                ->first();

            if ($firstAdmin && $firstAdmin->user_id == $userId) {
                return response()->json(['error' => 'Вы не можете изменить роль первого руководителя'], 403);
            }

            $userGroup->update(['role' => $request->input('newRole')]);

            return response()->json(['message' => 'Роль пользователя успешно обновлена']);

        } catch (ValidationException $e) {
            return response()->json(['error' => 'Произошла ошибка при валидации данных', 'errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Ошибка обновления роли пользователя', 'errors' => $e->getMessage()], 500);
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

    // Получение роли участника в группе
    private function getUserRole($userId, $groupId) {
        $groupExists = $this->groupIsExists($groupId);
        if (!$groupExists) {
            return 'Group does not exist';
        }

        $userGroup = User_Groups::where('user_id', $userId)
            ->where('group_id', $groupId)
            ->first();

        if (!$userGroup) {
            return 'User is not a member of the group';
        }

        return $userGroup->role;
    }

    // Существование группы
    private function groupIsExists($groupId) {
        $groupExists = Group::where('id', $groupId)->exists();
        if (!$groupExists) {
            return false;
        }
        return true;
    }

    private function isJson($string) {
        json_decode($string);
        return (json_last_error() == JSON_ERROR_NONE);
    }
}
