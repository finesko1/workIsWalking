<?php

namespace App\Http\Controllers\Friendship;

use App\Http\Controllers\Controller;
use App\Models\Friendship\Friendship;
use App\Models\User\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;


class FriendshipController extends Controller
{
    public function showAll() {
        try {
            $ownId = auth()->id();
            $users = User::where('id', '!=', $ownId)->get();

            $usersData = $users->map(function ($user) use ($ownId) {
                $imageUrl = $this->getUrl($user);

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
            if (request()->expectsJson()) {
                return response()->json([
                    'message' => 'all successfully',
                    'usersData' => $usersData,
                ], 200);
            }
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
            $friends = $user->friends()->whereNull('friendships.deleted_at')->get();;
            $user->friends->makeHidden('pivot');

            $friendsData = $friends->map(function ($user) {
                return $this->getUsersData($user);
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
            $pendings = $user->pendings()->whereNull('friendships.deleted_at')->get();;


            $pendingsData = $pendings->map(function ($user) {
                return $this->getUsersData($user);
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
            $followers= $user->followers()->whereNull('friendships.deleted_at')->get();;


            $followersData = $followers->map(function ($user) {
                return $this->getUsersData($user);
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
            $followings= $user->followings()->whereNull('friendships.deleted_at')->get();

            $followingsData = $followings->map(function ($user) {
                return $this->getUsersData($user);
            });
            if (request()->expectsJson()) {
                return response()->json([
                    'message' => 'all successfully',
                    'followingsData' => $followingsData,
                ], 200);
            }
            return view('welcome', [
                'message' => 'all successfully',
                'followingsData' => $followingsData,
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }


    public function showBlocked() {
        try {
            $user = auth()->user();
            $blockedUsers= $user->blocked()->whereNull('friendships.deleted_at')->get();;
            $blockedUsersData = $blockedUsers->map(function ($user) {
                return $this->getUsersData($user);
            });
            if (request()->expectsJson()) {
                return response()->json([
                    'message' => 'all successfully',
                    'blockedData' => $blockedUsersData,
                ], 200);
            }
            return view('welcome', [
                'message' => 'all successfully',
                'blockedData' => $blockedUsersData,
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }


    // Отправить заявку
    public function addFollowing($id) {
        try {
            DB::transaction(function() use ($id) {
                $userId = auth()->id();
                $friendshipRecordOne = Friendship::withTrashed()->where('user_id', $userId)->where('friend_id', $id)->first();
                $friendshipRecordTwo = Friendship::withTrashed()->where('user_id', $id)->where('friend_id', $userId)->first();

                if ($friendshipRecordOne) {
                    if ($friendshipRecordOne->trashed()) {
                        $friendshipRecordOne->restore();
                    }
                    $friendshipRecordOne->status = 'following';
                    $friendshipRecordOne->save();
                } else {
                    Friendship::create(['user_id' => $userId, 'friend_id' => $id, 'status' => 'following']);
                }
                if ($friendshipRecordTwo) {
                    if ($friendshipRecordTwo->trashed()) {
                        $friendshipRecordTwo->restore();
                    }
                    $friendshipRecordTwo->status = 'pending';
                    $friendshipRecordTwo->save();
                } else {
                    Friendship::create(['user_id' => $id, 'friend_id' => $userId, 'status' => 'pending']);
                }
            });

            if (request()->expectsJson()) {
                return response()->json([
                    'message' => 'all successfully',
                ], 200);
            }
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
            $friendshipRecordOne = Friendship::where('user_id', $userId)->where('friend_id', $id)->first();
            $friendshipRecordTwo = Friendship::where('user_id', $id)->where('friend_id', $userId)->first();

            if ($friendshipRecordOne && ($friendshipRecordOne->status === 'follower' || $friendshipRecordOne->status === 'pending')) {
                $friendshipRecordOne->status = 'accepted';
                $friendshipRecordOne->save();
            }
            // else Friendship::create(['user_id' => $id,  'friend_id' => $id, 'status' => 'accepted']);
            if ($friendshipRecordTwo && $friendshipRecordTwo->status === 'following') {
                $friendshipRecordTwo->status = 'accepted';
                $friendshipRecordTwo->save();
            }
            if (request()->expectsJson()) {
                return response()->json([
                    'message' => 'all successfully',
                ], 200);
            }
            return view('welcome', [
                'message' => 'all successfully',
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Status updated probably.'], 500);
        }
    }

    // Отклонить заявку в друзья, удалить друга
    public function addFollower($id) {
        try {
            $userId = auth()->id();
            // $friendshipRecordOne = Friendship::withTrashed()->where('user_id', $userId)->where('friend_id', $id)->first();
            $friendshipRecordOne = Friendship::where('user_id', $userId)->where('friend_id', $id)->first();
            $friendshipRecordTwo = Friendship::where('user_id', $id)->where('friend_id', $userId)->first();
            if ($friendshipRecordOne &&  ($friendshipRecordOne->status === 'pending' || $friendshipRecordOne->status === 'accepted')) {
                $friendshipRecordOne->status = 'follower';
                $friendshipRecordOne->save();
            }
            //$friendshipRecordOne = new Friendship();
            //$friendshipRecordOne->user_id = $userId;
            //$friendshipRecordOne->friend_id = $id;
            //$friendshipRecordOne->status = 'follower';
            //$friendshipRecordOne->save();
            //  or Friendship::create(['user_id' => $userId, 'friend_id' => $id, 'status' => 'follower']);
            if ($friendshipRecordTwo &&  ($friendshipRecordTwo->status === 'following' || $friendshipRecordTwo->status === 'accepted')) {
                $friendshipRecordTwo->status = 'following';
                $friendshipRecordTwo->save();
            }
            if (request()->expectsJson()) {
                return response()->json([
                    'message' => 'all successfully',
                ], 200);
            }
            return view('welcome', [
                'message' => 'all successfully',
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Status updated probably.'], 500);
        }
    }


    // Отменить отправленную заявку
    public function cancelFollowing($id) {
        try {
            DB::transaction(function () use ($id) {
                $userId = auth()->id();
                $friendshipRecordOne = Friendship::where('user_id', $userId)->where('friend_id', $id)->first();
                $friendshipRecordTwo = Friendship::where('user_id', $id)->where('friend_id', $userId)->first();
                if ($friendshipRecordOne && $friendshipRecordOne->status === 'following') {
                    $friendshipRecordOne->delete();
                }
                if ($friendshipRecordTwo && $friendshipRecordTwo->status === 'pending') {
                    $friendshipRecordTwo->delete();
                }
            });
            if (request()->expectsJson()) {
                return response()->json([
                    'message' => 'all successfully',
                ], 200);
            }
            return view('welcome', [
                'message' => 'all successfully',
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Status updated probably.'], 500);
        }
    }

    // Заблокировать пользователя
    public function blockIt($id) {
        try {
            DB::transaction(function() use ($id) {
                $userId = auth()->id();
                $friendshipRecordOne = Friendship::withTrashed()
                    ->where('user_id', $userId)
                    ->where('friend_id', $id)
                    ->first();
                $friendshipRecordTwo = Friendship::withTrashed()
                    ->where('user_id', $id)
                    ->where('friend_id', $userId)
                    ->first();

                if ($friendshipRecordOne) {
                    if ($friendshipRecordOne->status === 'blockMe') {
                        $friendshipRecordOne->status = 'blocked';
                    } else {
                        $friendshipRecordOne->status = 'blockIt';
                    }
                    if ($friendshipRecordOne->trashed()) {
                        $friendshipRecordOne->restore();
                    }
                    $friendshipRecordOne->save();
                } else {
                    Friendship::create([
                        'user_id' => $userId,
                        'friend_id' => $id,
                        'status' => 'blockIt'
                    ]);
                }

                if ($friendshipRecordTwo) {
                    if ($friendshipRecordTwo->status === 'blockIt') {
                        $friendshipRecordTwo->status = 'blocked';
                    } else {
                        $friendshipRecordTwo->status = 'blockMe';
                    }
                    if ($friendshipRecordOne->trashed()) {
                        $friendshipRecordOne->restore();
                    }
                    $friendshipRecordTwo->save();
                } else {
                    Friendship::create([
                        'user_id' => $id,
                        'friend_id' => $userId,
                        'status' => 'blockMe'
                    ]);
                }
            });

            if (request()->expectsJson()) {
                return response()->json([
                    'message' => 'all successfully',
                ], 200);
            }

            return view('welcome', [
                'message' => 'all successfully',
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Status updated probably.'], 500);
        }
    }

    // Разблокировать пользователя
    public function unblockIt($id) {
        try {
            DB::transaction(function() use ($id) {
                $userId = auth()->id();

                $friendshipRecordOne = Friendship::withTrashed()
                    ->where('user_id', $userId)
                    ->where('friend_id', $id)
                    ->first();
                $friendshipRecordTwo = Friendship::withTrashed()
                    ->where('user_id', $id)
                    ->where('friend_id', $userId)
                    ->first();

                /*
                 * if ($friendshipRecordOne->trashed()) {
                            $friendshipRecordOne->restore();
                        }
                 */
                if ($friendshipRecordOne) {
                    if ($friendshipRecordOne->status === 'blockIt') {
                        $friendshipRecordOne->delete();
                    } elseif ($friendshipRecordOne->status === 'blocked') {
                        $friendshipRecordOne->status = 'blockMe';
                        $friendshipRecordOne->save();
                    }
                }

                if ($friendshipRecordTwo) {
                    if ($friendshipRecordTwo->status === 'blockMe') {
                        $friendshipRecordTwo->delete();
                    } elseif ($friendshipRecordTwo->status === 'blocked') {
                        $friendshipRecordTwo->status = 'blockIt';
                        $friendshipRecordTwo->save();
                    }
                }
            });

            if (request()->expectsJson()) {
                return response()->json(['message' => 'Successfully unblocked.'], 200);
            }

            return view('welcome', ['message' => 'Successfully unblocked.']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Unblock action failed: ' . $e->getMessage()], 500);
        }
    }


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
