<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\User;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    public function loginStore(Request $request) {
        try {
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
                return response()->json([
                    'message' => 'User logged in successfully',
                ], 200);
            } else {
                return response()->json([
                    'message' => 'Invalid login credentials.',
                ], 401);  // HTTP код 401 для неавторизованного запроса
            }
        }
        catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation failed.',
                'errors' => $e->errors()
            ], 422);
        }
        catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ]);
        }
    }
}
