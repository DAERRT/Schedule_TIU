<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Faculty;

class FacultyController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:faculties,name',
        ], [
            'name.required' => 'Введите название кафедры',
            'name.unique' => 'Кафедра с таким названием уже существует',
        ]);

        Faculty::create($validated);

        return redirect()->back()->with('success', 'Кафедра успешно создана');
    }

    public function update(Request $request, Faculty $faculty)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:faculties,name,' . $faculty->id_faculties . ',id_faculties',
        ], [
            'name.required' => 'Введите название кафедры',
            'name.unique' => 'Кафедра с таким названием уже существует',
        ]);

        $faculty->update($validated);

        return redirect()->back()->with('success', 'Кафедра обновлена');
    }

    public function destroy(Faculty $faculty)
    {
        $faculty->delete();

        return redirect()->back()->with('success', 'Кафедра удалена');
    }
}
