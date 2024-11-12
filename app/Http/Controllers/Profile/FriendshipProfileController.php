<?php

namespace App\Http\Controllers\Profile;

use App\Http\Controllers\Controller;
use App\Models\User\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class FriendshipProfileController extends Controller
{
    public function show($id) {
        $user = User::where('id', $id)->first();
        if ($user) {
            $friendshipProfileData = $this->getUsersData($user);
            if (request()->expectsJson()) {
                return response()->json([
                    'message' => 'Пользователь найден',
                    'user' => $friendshipProfileData
                ], 200);
            }
            return view('welcome', [
                'message' => 'all successfully',
            ]);
        } else {
            return response()->json(['error' => 'Ошибка поиска'], 401);
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
