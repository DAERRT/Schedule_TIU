<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    protected $primaryKey = 'id_groups';

    /**
     * Подгруппа, к которой принадлежит группа
     */
    public function subgroup()
    {
        return $this->belongsTo(Subgroup::class, 'id_subgroups', 'id_subgroups');
    }

    /**
     * Учебный лист, к которому принадлежит группа
     */
    public function worksheet()
    {
        return $this->belongsTo(Worksheet::class, 'id_worksheets', 'id_worksheets');
    }

    /**
     * Занятия, принадлежащие группе
     */
    public function lessons()
    {
        return $this->hasMany(Lesson::class, 'id_groups', 'id_groups');
    }
}
