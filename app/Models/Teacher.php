<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
    protected $primaryKey = 'id_teachers';

    protected $fillable = [
        'first_name',
        'last_name',
        'middle_name',
        'full_name',
        'telephone',
        'email',
        'isActive',
        'isVacation',
    ];

    protected $appends = ['name'];

    /**
     * Аксессор — позволяет использовать teacher.name вместо teacher.full_name
     */
    public function getNameAttribute(): string
    {
        return $this->full_name;
    }

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
