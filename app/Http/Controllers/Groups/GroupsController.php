<?php

namespace App\Http\Controllers\Groups;

use App\Http\Controllers\Controller;
use App\Models\Group\Group;
use App\Models\Group\Material;
use App\Models\Group\User_Groups;
use App\Models\User\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class GroupsController extends Controller
{
    // Создание группы
    public function create(Request $request) {
        try {
            $user = auth()->user();
            if (!$user) {
                return response()->json(['error' => 'Пользователь не авторизован'], 401);
            }

            $validateData = $request->validate([
                'groupName' => 'required|string|max:255'
            ]);

            DB::transaction(function () use ($validateData, $user) {
                // Создание группы
                $handlerGroup = new Group();
                $handlerGroup->name = $validateData['groupName'];
                $handlerGroup->save();

                // Добавление участника в группу
                $handlerUserGroups = new User_Groups();
                $handlerUserGroups->role = 'admin';
                $handlerUserGroups->user_id = $user->id;
                $handlerUserGroups->group_id = $handlerGroup->id;
                $handlerUserGroups->save();
            });

            if (request()->expectsJson()) {
                return response()->json(['message' => 'Группа успешно создана']);
            } else {
                return view('welcome');
            }
        }
        catch (ValidationException $e) {
            return response()->json(['error' => 'Ошибка ввода данных',
                                     'errors' => $e->errors()
            ], 422);
        }
        catch (\Exception $e) {
            if (request()->expectsJson()) {
                return response()->json(['error' => 'Попробуйте перезагрузить страницу'
                ], 500);
            }
            return view('welcome');
        }
    }

    public function showGroups() {
        try {
            $user = auth()->user();
            if (!$user) {
                return response()->json(['error' => 'Ошибка авторизации'], 401);
            }

            // Получаем ID групп, в которых состоит пользователь
            $idGroups = User_Groups::where('user_id', $user->id)->select('group_id')->get();

            // Получаем названия групп
            $nameGroups = Group::whereIn('id', $idGroups)->get(['id', 'name']);

            $resultData = [];

            foreach ($nameGroups as $group) {
                // Получаем количество пользователей в группе
                $countUsersInGroup = User_Groups::where('group_id', $group->id)->count();

                // Добавляем данные в результирующий массив
                $resultData[] = [
                    'group_id' => $group->id,
                    'groupName' => $group->name,
                    'countUsers' => $countUsersInGroup,
                ];
            }


            if(request()->expectsJson()) {
                return response()->json(['message' => 'Запрос выполнен успешно',
                    'groupsData' => $resultData
                ], 200);
            }
            return view('welcome', ['groupsData' => $resultData]);

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
