<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Year extends Model
{
    protected $primaryKey = 'id_years';

    protected $fillable = [
        'year_start',
        'year_end',
    ];

    /**
     * Занятия, которые принадлежат учебному году
     */
    public function lessons()
    {
        return $this->hasMany(Lesson::class, 'id_years', 'id_years');
    }

    /**
     * Группы, которые привязаны к году через занятия
     */
    public function groups()
    {
        return $this->hasManyThrough(
            Group::class,
            Lesson::class,
            'id_years',
            'id_groups',
            'id_years',
            'id_groups'
        );
    }
}
