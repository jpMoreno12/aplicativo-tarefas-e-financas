<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $response = Http::post(config('services.authenticator.url') . '/oauth/token', [
            "grant_type" => "password",
            "client_id" => env("CLIENT_ID"),
            "client_secret" => env("CLIENT_SECRET"),
            "username" => $validated['email'],
            "password" => $validated['password'],
            "scope" => ""
        ]);

        $data = $response->json();

        $accessToken = $data['access_token'] ?? null;
        $refreshToken = $data['refresh_token'] ?? null;


        $userResponse = Http::withToken($accessToken)->get(config('services.authenticator.url') . '/api/user/check');

        if ($userResponse->successful()) {
            $data = $userResponse->json();

            // Salva o token na sessão
            session([
                'access_token' => $accessToken,
                'refresh_token' => $refreshToken
            ]);
            // dd(redirect()->intended(route('dashboard')));
            return redirect()->intended(route('dashboard'));
        }

        // if (Auth::attempt($request->only('email', 'password'), $request->filled('remember'))) {
        //     return redirect()->intended(route('dashboard'));
        // }

        return back()->withErrors([
            'email' => 'As credenciais fornecidas não correspondem aos nossos registros.',
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
