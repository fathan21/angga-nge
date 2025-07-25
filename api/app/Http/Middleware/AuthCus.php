<?php

namespace App\Http\Middleware;

use App\Models\Pelanggan;
use App\Models\User;
use Closure;
use Illuminate\Container\Attributes\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Response;

class AuthCus
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $headers = $request->header();
        $xauth = @$headers['x-auth'][0];
        if(!$xauth) {
            abort(401,'no auth');
        }
        $xauth = explode('_',$xauth);
        $cache = Cache::get('access_token', $xauth[0]);
        if(!$cache) {
            abort(401,'no auth');
        }

        $user = User::find($xauth[0]);
        if(!$user) {
            $pelanggan = Pelanggan::find($xauth[1]);
            if(!$pelanggan) {
                abort(401,'no auth');
            }
            $user = $pelanggan;
            $user->username = $pelanggan->nama;
            $user->id = $pelanggan->id_pelanggan;
            $user->role = 'pelanggan';
        }else{
            $user->role = 'admin';
        }
        
        request()->merge(['auth_user'=>$user]);

        return $next($request);
    }
}
