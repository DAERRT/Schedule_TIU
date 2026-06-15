<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    protected $primaryKey = 'id_subjects';

    /**
     * Занятия, принадлежащие предмету
     */
    public function lessons()
    {
        return $this->hasMany(Lesson::class, 'id_subjects', 'id_subjects');
    }
}
