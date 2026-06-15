<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Building extends Model
{
    protected $primaryKey = 'id_buildings';

    /**
     * Аудитории, принадлежащие корпусу
     */
    public function rooms()
    {
        return $this->hasMany(Room::class, 'id_buildings', 'id_buildings');
    }
}
