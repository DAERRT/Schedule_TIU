<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Worksheet;

class WorksheetController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:worksheets,name',
        ], [
            'name.required' => 'Введите название рабочего листа',
            'name.unique' => 'Рабочий лист с таким названием уже существует',
        ]);

        Worksheet::create($validated);

        return redirect()->back()->with('success', 'Рабочий лист успешно создан');
    }

    public function update(Request $request, Worksheet $worksheet)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:worksheets,name,' . $worksheet->id_worksheets . ',id_worksheets',
        ], [
            'name.required' => 'Введите название рабочего листа',
            'name.unique' => 'Рабочий лист с таким названием уже существует',
        ]);

        $worksheet->update($validated);

        return redirect()->back()->with('success', 'Рабочий лист обновлен');
    }

    public function destroy(Worksheet $worksheet)
    {
        $worksheet->delete();

        return redirect()->back()->with('success', 'Рабочий лист удален');
    }
}
