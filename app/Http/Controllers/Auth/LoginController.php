<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\User;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function loginStore(Request $request) {
        $request->validate([
            'login' => 'required|string',
            'password' => 'required|string',
        ]);

        $login = $request->input('login');
        $pswd = $request->input('password');

        $user = User::where('login', $login)
            ->orWhere('email', $login)
            ->first();

        if ($user && Hash::check($pswd, $user->password)) {
            Auth::login($user);
            return response()->json(['message' => 'Feedback user', 'user' => $user->login]);
        } else {
            return response()->json(['message' => 'Invalid credentials.'], 401);
        }
    }
}
