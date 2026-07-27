<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Group;

class GroupController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'id_worksheets' => 'required|exists:worksheets,id_worksheets',
            'id_subgroups' => 'nullable|exists:subgroups,id_subgroups',
            'stud_num' => 'nullable|integer',
        ], [
            'name.required' => 'Введите название группы',
            'id_worksheets.required' => 'Выберите рабочий лист',
        ]);

        $group = new Group();
        $group->name = $validated['name'];
        $group->id_worksheets = $validated['id_worksheets'];
        $group->id_subgroups = $validated['id_subgroups'] ?? null;
        $group->stud_num = $validated['stud_num'] ?? null;
        $group->save();

        return redirect()->back()->with('success', 'Группа успешно создана');
    }

    public function update(Request $request, Group $group)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'id_worksheets' => 'required|exists:worksheets,id_worksheets',
            'id_subgroups' => 'nullable|exists:subgroups,id_subgroups',
            'stud_num' => 'nullable|integer',
        ], [
            'name.required' => 'Введите название группы',
            'id_worksheets.required' => 'Выберите рабочий лист',
        ]);

        $group->name = $validated['name'];
        $group->id_worksheets = $validated['id_worksheets'];
        $group->id_subgroups = $validated['id_subgroups'] ?? null;
        $group->stud_num = $validated['stud_num'] ?? null;
        $group->save();

        return redirect()->back()->with('success', 'Группа обновлена');
    }

    public function destroy(Group $group)
    {
        $group->delete();

        return redirect()->back()->with('success', 'Группа удалена');
    }
}
