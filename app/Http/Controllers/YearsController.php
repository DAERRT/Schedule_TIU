<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Year;
use Inertia\Inertia;
//
//Контроллер для работы с годами.
//
class YearsController extends Controller
{
    public function index()
    {
        $years = Year::all();
        return Inertia::render('Years/Index', [
            'years' => $years
        ]);
    }
}
