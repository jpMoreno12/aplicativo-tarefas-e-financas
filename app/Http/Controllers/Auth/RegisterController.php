<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;

class RegisterController extends Controller
{
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);
        
        $response = Http::post(config('services.authenticator.url') . '/api/user/create', [
            "name" => $validated['name'],
            "email" => $validated['email'],
            "password" => $validated['password']
        ]);

        if (!$response->successful()) {
            return back()
                ->withErrors(['email' => 'Este e-mail já está cadastrado.'])
                ->withInput();
        }


        //$user = User::create([
        //    'name' => $request->name,
        //    'email' => $request->email,
        //    'password' => Hash::make($request->password),
        //]);

        //Auth::login($user);

        return redirect()->route('dashboard');
    }
}
