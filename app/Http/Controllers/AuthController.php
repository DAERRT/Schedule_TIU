<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use App\Http\Requests\LoginRequest;
//
// Контроллер для аутентификации и авторизации пользователей.
//
class AuthController extends Controller
{
    // Get /login
    // Показать форму входа
    public function showLoginForm(){
        return Inertia::render('Auth/Login');
    }

    // Post /login
    // Обработать данные формы входа и аутентифицировать пользователя
    public function login(LoginRequest $request){
        $credentials = $request->validated();

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->route('home')->with('success', 'You are logged in!');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ]);
    }

    // Post /logout
    // Выйти из системы
    public function logout(Request $request){
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login.show')->with('success', 'You are logged out!');
    }
}
