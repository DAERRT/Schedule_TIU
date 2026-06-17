<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Year extends Model
{
    protected $primaryKey = 'id_years';

    /**
     * Занятия, которые принадлежат учебному году
     */
    public function lessons()
    {
        return $this->hasMany(Lesson::class, 'id_years', 'id_years');
    }
}
