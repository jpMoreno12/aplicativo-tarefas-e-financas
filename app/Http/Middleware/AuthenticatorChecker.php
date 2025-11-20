<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Auth\GenericUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class AuthenticatorChecker
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $token = session('access_token'); // retorna o access_token
        
        $data = Http::withToken($token)->get(env('AUTHENTICATOR') . '/api/user/check');
        
        if ($data->failed()) {
            return response()->json(['error' => 'Invalid token'], 401);
        }


        $user = new GenericUser($data->json());
                
        Auth::setUser($user);

        // Opcional: se quiser acessar depois via $request->user()
        $request->setUserResolver(fn() => $user);

        return $next($request);
    }
}
