<?php

namespace App\Http\Controllers;

use App\Models\Subgroup;
use Illuminate\Http\Request;

class SubgroupController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:subgroups,name',
        ], [
            'name.required' => 'Введите название подгруппы',
        ]);

        $subgroup = Subgroup::create($validated);

        return redirect()->back()->with('success', 'Подгруппа успешно создана');
    }

    public function update(Request $request, Subgroup $subgroup)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:subgroups,name,' . $subgroup->id_subgroups . ',id_subgroups',
        ], [
            'name.required' => 'Введите название подгруппы',
        ]);

        $subgroup->update($validated);

        return redirect()->back()->with('success', 'Подгруппа обновлена');
    }

    public function destroy(Subgroup $subgroup)
    {
        $subgroup->delete();

        return redirect()->back()->with('success', 'Подгруппа удалена');
    }
}