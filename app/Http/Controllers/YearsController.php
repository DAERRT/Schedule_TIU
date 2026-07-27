<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Year;
use App\Models\Building;
use App\Models\Faculty;
use App\Models\Subject;
use App\Models\Worksheet;
use Inertia\Inertia;
//
//Контроллер для работы с годами.
//
class YearsController extends Controller
{
    public function index()
    {
        $years = Year::withCount(['groups'])->get();

        return Inertia::render('Years/Index', [
            'years' => $years,
        ]);
    }

    public function show(Year $year)
    {
        $buildings = Building::with('rooms')->orderBy('name')->get();
        $faculties = Faculty::with('teachers')->orderBy('name')->get();
        $worksheets = Worksheet::with('groups')->orderBy('name')->get();
        $subgroups = \App\Models\Subgroup::orderBy('name')->get();
        $subjects = Subject::orderBy('name')->get();

        $lessons = $year->lessons()
            ->with(['subgroup', 'room', 'teacher', 'subject', 'group'])
            ->get();

        return Inertia::render('Years/Show', [
            'year' => $year,
            'buildings' => $buildings,
            'faculties' => $faculties,
            'worksheets' => $worksheets,
            'subgroups' => $subgroups,
            'subjects' => $subjects,
            'lessons' => $lessons,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'year_start' => 'required|digits:4',
            'year_end' => 'required|digits:4|gte:year_start',
        ], [
            'year_start.required' => 'Введите год начала',
            'year_start.digits' => 'Год начала должен содержать 4 цифры',
            'year_end.required' => 'Введите год окончания',
            'year_end.digits' => 'Год окончания должен содержать 4 цифры',
            'year_end.gte' => 'Год окончания должен быть не меньше года начала',
        ]);

        Year::create($validated);

        return redirect()->route('years.index')->with('success', 'Год успешно создан');
    }

    public function destroy(Year $year)
    {
        if ($year->groups()->exists()) {
            return back()->with('error', 'Нельзя удалить год, пока к нему привязаны группы.');
        }

        $year->delete();

        return redirect()->route('years.index')->with('success', 'Год успешно удален');
    }
}
