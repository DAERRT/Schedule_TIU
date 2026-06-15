<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
    protected $primaryKey = 'id_teachers';

    /**
     * Факультеты, принадлежащие преподавателю через таблицу teachers_faculties
     */
    public function faculties()
    {
        return $this->belongsToMany(Faculty::class, 'teachers_faculties', 'id_teachers', 'id_faculties', 'id_teachers', 'id_faculties');
    }

    /**
     * Занятия, которые ведет преподаватель
     */
    public function lessons()
    {
        return $this->hasMany(Lesson::class, 'id_teachers', 'id_teachers');
    }
}
