<?php

namespace App\Http\Controllers\Profile;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
class UserProfileController extends Controller
{
    public function showProfileData() {
        $user = Auth::user();

        return response()->json([
            'login' => $user->login,
            'email' => $user->email,
        ]);
    }
}
