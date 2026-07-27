<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Room;
use App\Models\Building;
use Inertia\Inertia;

class RoomController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'id_buildings' => 'required|exists:buildings,id_buildings',
        ]);

        Room::create($validated);

        return redirect()->back()->with('success', 'Аудитория успешно создана');
    }

    public function update(Request $request, Room $room)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'id_buildings' => 'required|exists:buildings,id_buildings',
        ]);

        $room->update($validated);

        return redirect()->back()->with('success', 'Аудитория обновлена');
    }

    public function destroy(Room $room)
    {
        if ($room->lessons()->exists()) {
            return redirect()->back()->with('error', 'Нельзя удалить аудиторию с занятиями.');
        }

        $room->delete();

        return redirect()->back()->with('success', 'Аудитория удалена');
    }
}
