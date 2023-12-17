<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function Login(Request $request)
    {
        $credentials = request(['email', 'password']);
        if (!Auth::attempt($credentials))
            return 'Unauthorized';

        $User = $request->user();
        $AccessToken = $User->createToken('Task')->accessToken;

        return [
            'User' => $User,
            'AccessToken' => $AccessToken,
        ];
    }
}
