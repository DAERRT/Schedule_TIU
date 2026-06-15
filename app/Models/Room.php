<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    protected $primaryKey = 'id_rooms';

    /**
     * Корпус, к которому принадлежит аудитория
     */
    public function building()
    {
        return $this->belongsTo(Building::class, 'id_buildings', 'id_buildings');
    }

    /**
     * Занятия, проходящие в аудитории
     */
    public function lessons()
    {
        return $this->hasMany(Lesson::class, 'id_rooms', 'id_rooms');
    }
}
