<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Faculty extends Model
{
    protected $primaryKey = 'id_faculties';

    /**
     * Преподаватели, принадлежащие факультету через таблицу teachers_faculties
     */
    public function teachers()
    {
        return $this->belongsToMany(Teacher::class, 'teachers_faculties', 'id_faculties', 'id_teachers', 'id_faculties', 'id_teachers');
    }
}
