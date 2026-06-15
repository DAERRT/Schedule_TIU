<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
//
//Контроллер для теста формы логин
//
class HomeController extends Controller
{
    // Get /
    // Показать главную страницу
    public function index(){
        return Inertia::render('Home');
    }
}
