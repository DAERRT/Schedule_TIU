<?php

namespace App\Http\Controllers;

use App\Models\Lesson;
use Illuminate\Http\Request;
use Inertia\Inertia;

class LessonController extends Controller
{
    /**
     * Store a newly created lesson.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_groups' => 'required|exists:groups,id_groups',
            'id_years' => 'required|exists:years,id_years',
            'id_subgroups' => 'nullable|exists:subgroups,id_subgroups',
            'id_rooms' => 'required|exists:rooms,id_rooms',
            'id_teachers' => 'required|exists:teachers,id_teachers',
            'id_subjects' => 'required|exists:subjects,id_subjects',
            'week_parity' => 'required|in:odd,even,both',
            'week_day' => 'required|integer|min:1|max:7',
            'order_number' => 'required|integer|min:1',
            'isZaoch' => 'boolean',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date',
        ]);

        $lesson = Lesson::create($validated);

        return back()->with('success', 'Занятие успешно создано');
    }

    /**
     * Update the specified lesson.
     */
    public function update(Request $request, Lesson $lesson)
    {
        $validated = $request->validate([
            'id_groups' => 'required|exists:groups,id_groups',
            'id_years' => 'required|exists:years,id_years',
            'id_subgroups' => 'nullable|exists:subgroups,id_subgroups',
            'id_rooms' => 'required|exists:rooms,id_rooms',
            'id_teachers' => 'required|exists:teachers,id_teachers',
            'id_subjects' => 'required|exists:subjects,id_subjects',
            'week_parity' => 'required|in:odd,even,both',
            'week_day' => 'required|integer|min:1|max:7',
            'order_number' => 'required|integer|min:1',
            'isZaoch' => 'boolean',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date',
        ]);

        $lesson->update($validated);

        return back()->with('success', 'Занятие успешно обновлено');
    }

    /**
     * Remove the specified lesson.
     */
    public function destroy(Lesson $lesson)
    {
        $lesson->delete();

        return back()->with('success', 'Занятие успешно удалено');
    }
}