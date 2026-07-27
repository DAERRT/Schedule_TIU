<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Teacher;

class TeacherController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'last_name' => 'required|string|max:255',
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255|unique:teachers,email',
            'telephone' => 'nullable|string|max:255',
            'faculty_id' => 'nullable|exists:faculties,id_faculties',
        ], [
            'last_name.required' => 'Введите фамилию',
            'first_name.required' => 'Введите имя',
        ]);

        $teacher = new Teacher();
        $teacher->last_name = $validated['last_name'];
        $teacher->first_name = $validated['first_name'];
        $teacher->middle_name = $validated['middle_name'] ?? null;
        $teacher->email = $validated['email'] ?? null;
        $teacher->telephone = $validated['telephone'] ?? null;
        $teacher->full_name = $this->generateFullName(
            $validated['last_name'],
            $validated['first_name'],
            $validated['middle_name'] ?? null,
        );
        $teacher->save();

        if ($validated['faculty_id']) {
            $teacher->faculties()->attach($validated['faculty_id']);
        }

        return redirect()->back()->with('success', 'Преподаватель успешно создан');
    }

    public function update(Request $request, Teacher $teacher)
    {
        $validated = $request->validate([
            'last_name' => 'required|string|max:255',
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255|unique:teachers,email,' . $teacher->id_teachers . ',id_teachers',
            'telephone' => 'nullable|string|max:255',
            'faculty_id' => 'nullable|exists:faculties,id_faculties',
        ], [
            'last_name.required' => 'Введите фамилию',
            'first_name.required' => 'Введите имя',
        ]);

        $teacher->last_name = $validated['last_name'];
        $teacher->first_name = $validated['first_name'];
        $teacher->middle_name = $validated['middle_name'] ?? null;
        $teacher->email = $validated['email'] ?? null;
        $teacher->telephone = $validated['telephone'] ?? null;
        $teacher->full_name = $this->generateFullName(
            $validated['last_name'],
            $validated['first_name'],
            $validated['middle_name'] ?? null,
        );
        $teacher->save();

        if ($validated['faculty_id']) {
            $teacher->faculties()->sync([$validated['faculty_id']]);
        }

        return redirect()->back()->with('success', 'Преподаватель обновлен');
    }

    public function destroy(Teacher $teacher)
    {
        $teacher->delete();

        return redirect()->back()->with('success', 'Преподаватель удален');
    }

    /**
     * Формирует full_name в формате "Фамилия И.О."
     */
    private function generateFullName(string $lastName, string $firstName, ?string $middleName): string
    {
        $fullName = $lastName . ' ' . mb_substr($firstName, 0, 1) . '.';

        if ($middleName) {
            $fullName .= ' ' . mb_substr($middleName, 0, 1) . '.';
        }

        return $fullName;
    }
}
