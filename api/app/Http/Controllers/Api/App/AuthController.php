<?php

namespace App\Http\Controllers\Api\App;

use App\Classes\ApiResponseClass;
use App\Http\Controllers\Api\ApiController;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class AuthController extends ApiController
{
    public function login(Request $request) {

        $request->validate([
            'username' => 'required',
            'password' => 'required'
        ]);
        
        $user = User::where('username', $request->get('username'))->first();
        if (!$user) {
            return $this->error(422, '#4 salah username atau password');
        }

        $account = ['username' => $user->username, 'password' => $request->get('password')];
        
        
        if ($account['password'] !=$user['password']) {
            return $this->error(422, '#5 salah username atau password');
        }
        // dd($user);
        $tokenDt = $user->id.'_'.random_string(20);
        $token = [
            'access_token' => $tokenDt
        ];
        Cache::set('access_token'.$user->id,$tokenDt, now()->addDays(10));
        $result = [
            ...$user->only(['username']),
            ...$token
        ];
        return $this->success($result);
    }
}
