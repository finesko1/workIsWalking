<?php

namespace App\Http\Controllers\Friendship;

use App\Http\Controllers\Controller;
use App\Models\Friendship\Friendship;
use App\Models\User\User;
use Illuminate\Support\Facades\Storage;


class FriendshipController extends Controller
{
    public function showAll() {
        try {
            $ownId = auth()->id();
            $users = User::where('id', '!=', $ownId)->get();

            $usersData = $users->map(function ($user) use ($ownId) {
                $imageUrl = $this->getUrl($user);

                // Проверка на наличие personalData
                if ($user->personalData && $user->personalData->first_name && $user->personalData->second_name) {
                    return [
                        'id' => $user->id,
                        'first_name' => $user->personalData->first_name,
                        'second_name' => $user->personalData->second_name,
                        'image_url' => $imageUrl ? $imageUrl : null,
                        'status' => Friendship::where('user_id', $ownId)->where('friend_id', $user->id)->value('status'),
                    ];
                } else {
                    return [
                        'id' => $user->id,
                        'login' => $user->login,
                        'image_url' => $imageUrl ? $imageUrl : null,
                        'status' => Friendship::where('user_id', $ownId)->where('friend_id', $user->id)->value('status'),
                    ];
                }
            });

            // Если запрос ожидает JSON (например, через Axios)
            if (request()->expectsJson()) {
                return response()->json([
                    'message' => 'all successfully',
                    'usersData' => $usersData,
                ], 200);
            }

            // Если запрос был сделан через URL в браузере
            return view('welcome', [
                'message' => 'all successfully',
                'usersData' => $usersData,
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }


    public function showFriends() {
        try {
            $user = auth()->user();
            $friends = $user->friends;
            $user->friends->makeHidden('pivot');

            $friendsData = $friends->map(function ($friend) {
                if($friend->personalData->first_name && $friend->personalData->second_name) {
                    // ссылка на фото профиля
                    $imageUrl = $this->getUrl($friend);
                    return [
                        'id' => $friend->id,
                        'first_name' => $friend->personalData->first_name,
                        'second_name' => $friend->personalData->second_name,
                        'image_url' => $imageUrl ? $imageUrl : null,
                    ];
                } else {
                    // ссылка на фото профиля
                    $imageUrl = $this->getUrl($friend);
                    return [
                        'id' => $friend->id,
                        'login' => $friend->login,
                        'image_url' => $imageUrl ? $imageUrl : null,
                    ];
                }
            });

            // Если запрос ожидает JSON (например, через Axios)
            if (request()->expectsJson()) {
                return response()->json([
                    'message' => 'all successfully',
                    'friendsData' => $friendsData,
                ], 200);
            }

            // Если запрос был сделан через URL в браузере
            return view('welcome', [
                'message' => 'all successfully',
                'friendsData' => $friendsData,
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }


    public function showPendings() {
        try {
            $user = auth()->user();
            $pendings = $user->pendings;


            $pendingsData = $pendings->map(function ($user) {
                if ($user->personalData->first_name && $user->personalData->second_name) {
                    // ссылка на фото профиля
                    $imageUrl = $this->getUrl($user);
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
            });

            // Если запрос ожидает JSON (например, через Axios)
            if (request()->expectsJson()) {
                return response()->json([
                    'message' => 'all successfully',
                    'pendingsData' => $pendingsData,
                ], 200);
            }

            // Если запрос был сделан через URL в браузере
            return view('welcome', [
                'message' => 'all successfully',
                'pendingsData' => $pendingsData,
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }


    public function showFollowers() {
        try {
            $user = auth()->user();
            $followers= $user->followers;


            $followersData = $followers->map(function ($user) {
                if ($user->personalData && $user->personalData->first_name && $user->personalData->second_name) {
                    // ссылка на фото профиля
                    $imageUrl = $this->getUrl($user);
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
            });

            // Если запрос ожидает JSON (например, через Axios)
            if (request()->expectsJson()) {
                return response()->json([
                    'message' => 'all successfully',
                    'followersData' => $followersData,
                ], 200);
            }

            // Если запрос был сделан через URL в браузере
            return view('welcome', [
                'message' => 'all successfully',
                'followersData' => $followersData,
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }


    public function showFollowings() {
        try {
            $user = auth()->user();
            $followings= $user->followings;

            $followingsData = $followings->map(function ($user) {
                if ($user->personalData && $user->personalData->first_name && $user->personalData->second_name) {
                    $imageUrl = $this->getUrl($user);
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
            });
            // Если запрос ожидает JSON (например, через Axios)
            if (request()->expectsJson()) {
                return response()->json([
                    'message' => 'all successfully',
                    'followingsData' => $followingsData,
                ], 200);
            }

            // Если запрос был сделан через URL в браузере
            return view('welcome', [
                'message' => 'all successfully',
                'followingsData' => $followingsData,
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }


    // Отправить заявку
    public function addFollowing($id) {
        try {
            $userId = auth()->id();
            $friendshipRecordOne = Friendship::where('user_id', $userId)->where('friend_id', $id)->first();
            $friendshipRecordTwo = Friendship::where('friend_id', $id)->where('user_id', $userId)->first();
            // Если первая запись найдена, обновляем статус
            if ($friendshipRecordOne) {
                $friendshipRecordOne->status = 'following';
                $friendshipRecordOne->save();
            } else {
                // Если первой записи нет, создаем ее
                $friendshipRecordOne = new Friendship();
                $friendshipRecordOne->user_id = $userId;
                $friendshipRecordOne->friend_id = $id;
                $friendshipRecordOne->status = 'following';
                $friendshipRecordOne->save();
            }
            // Если вторая запись найдена, обновляем статус
            if ($friendshipRecordTwo) {
                $friendshipRecordTwo->status = 'pending';
                $friendshipRecordTwo->save();
            } else {
                // Если второй записи нет, создаем ее
                $friendshipRecordTwo = new Friendship();
                $friendshipRecordTwo->user_id = $id;
                $friendshipRecordTwo->friend_id = $userId;
                $friendshipRecordTwo->status = 'pending';
                $friendshipRecordTwo->save();
            }

            // Если запрос ожидает JSON (например, через Axios)
            if (request()->expectsJson()) {
                return response()->json([
                    'message' => 'all successfully',
                ], 200);
            }

            // Если запрос был сделан через URL в браузере
            return view('welcome', [
                'message' => 'all successfully',
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Status updated probably.'], 500);
        }
    }

    // Принять в друзья
    public function addFriend($id) {
        try {
            $userId = auth()->id();

            // Поиск существующих записей
            $friendshipRecordOne = Friendship::where('user_id', $userId)->where('friend_id', $id)->first();
            $friendshipRecordTwo = Friendship::where('user_id', $id)->where('friend_id', $userId)->first();

            // Если первая запись найдена, обновляем статус
            if ($friendshipRecordOne) {
                $friendshipRecordOne->status = 'accepted';
                $friendshipRecordOne->save();
            } else {
                // Если первой записи нет, создаем ее
                $friendshipRecordOne = new Friendship();
                $friendshipRecordOne->user_id = $userId;
                $friendshipRecordOne->friend_id = $id;
                $friendshipRecordOne->status = 'accepted';
                $friendshipRecordOne->save();
            }
            // Если вторая запись найдена, обновляем статус
            if ($friendshipRecordTwo) {
                $friendshipRecordTwo->status = 'accepted';
                $friendshipRecordTwo->save();
            } else {
                // Если второй записи нет, создаем ее
                $friendshipRecordTwo = new Friendship();
                $friendshipRecordTwo->user_id = $id;
                $friendshipRecordTwo->friend_id = $userId;
                $friendshipRecordTwo->status = 'accepted';
                $friendshipRecordTwo->save();
            }
            // Если запрос ожидает JSON (например, через Axios)
            if (request()->expectsJson()) {
                return response()->json([
                    'message' => 'all successfully',
                ], 200);
            }

            // Если запрос был сделан через URL в браузере
            return view('welcome', [
                'message' => 'all successfully',
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Status updated probably.'], 500);
        }
    }

    // Отклонить заявку, удалить друга
    public function addFollower($id) {
        try {
            $userId = auth()->id();
            $friendshipRecordOne = Friendship::where('user_id', $userId)->where('friend_id', $id)->first();
            $friendshipRecordTwo = Friendship::where('user_id', $id)->where('friend_id', $userId)->first();
            // Если первая запись найдена, обновляем статус
            if ($friendshipRecordOne) {
                $friendshipRecordOne->status = 'follower';
                $friendshipRecordOne->save();
            } else {
                // Если первой записи нет, создаем ее
                $friendshipRecordOne = new Friendship();
                $friendshipRecordOne->user_id = $userId;
                $friendshipRecordOne->friend_id = $id;
                $friendshipRecordOne->status = 'follower';
                $friendshipRecordOne->save();
            }
            // Если вторая запись найдена, обновляем статус
            if ($friendshipRecordTwo) {
                $friendshipRecordTwo->status = 'following';
                $friendshipRecordTwo->save();
            } else {
                // Если второй записи нет, создаем ее
                $friendshipRecordTwo = new Friendship();
                $friendshipRecordTwo->user_id = $id;
                $friendshipRecordTwo->friend_id = $userId;
                $friendshipRecordTwo->status = 'following';
                $friendshipRecordTwo->save();
            }
            // Если запрос ожидает JSON (например, через Axios)
            if (request()->expectsJson()) {
                return response()->json([
                    'message' => 'all successfully',
                ], 200);
            }

            // Если запрос был сделан через URL в браузере
            return view('welcome', [
                'message' => 'all successfully',
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Status updated probably.'], 500);
        }
    }

    // Заблокировать пользователя

    /**
     * @param $user
     * @return string
     */
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
}
