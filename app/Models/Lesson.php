<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lesson extends Model
{
    protected $primaryKey = 'id_lessons';

    /**
     * Группа, к которой принадлежит занятие
     */
    public function group()
    {
        return $this->belongsTo(Group::class, 'id_groups', 'id_groups');
    }

    /**
     * Подгруппа, к которой принадлежит занятие
     */
    public function subgroup()
    {
        return $this->belongsTo(Subgroup::class, 'id_subgroups', 'id_subgroups');
    }

    /**
     * Аудитория, к которой принадлежит занятие
     */
    public function room()
    {
        return $this->belongsTo(Room::class, 'id_rooms', 'id_rooms');
    }

    /**
     * Преподаватель, к которому принадлежит занятие
     */
    public function teacher()
    {
        return $this->belongsTo(Teacher::class, 'id_teachers', 'id_teachers');
    }

    /**
     * Предмет, к которому принадлежит занятие
     */
    public function subject()
    {
        return $this->belongsTo(Subject::class, 'id_subjects', 'id_subjects');
    }
}
