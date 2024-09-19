<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;

class CheckAuthController extends Controller
{
    public function checkAuth(Request $request) {
        $user = Auth::user();
        if ($user) {
            return response()->json($user);
        } else {
            return response()->json(['message' => 'Unauthorized'], 401);
        }
    }
}
