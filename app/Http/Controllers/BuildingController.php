<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Building;
use App\Models\Lesson;
use Inertia\Inertia;

class BuildingController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        Building::create($validated);

        return redirect()->back()->with('success', 'Здание успешно создано');
    }

    public function update(Request $request, Building $building)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $building->update($validated);

        return redirect()->back()->with('success', 'Здание обновлено');
    }

    public function destroy(Building $building)
    {
        DB::transaction(function () use ($building) {
            $roomIds = $building->rooms()->pluck('id_rooms');

            if ($roomIds->isNotEmpty()) {
                Lesson::whereIn('id_rooms', $roomIds)->delete();
            }

            $building->rooms()->delete();
            $building->delete();
        });

        return redirect()->back()->with('success', 'Здание и связанные аудитории удалены');
    }
}
