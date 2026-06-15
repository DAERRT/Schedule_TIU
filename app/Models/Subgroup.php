<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subgroup extends Model
{
    protected $primaryKey = 'id_subgroups';

    /**
     * Группы, принадлежащие подгруппе
     */
    public function groups()
    {
        return $this->hasMany(Group::class, 'id_subgroups', 'id_subgroups');
    }

    /**
     * Занятия, принадлежащие подгруппе
     */
    public function lessons()
    {
        return $this->hasMany(Lesson::class, 'id_subgroups', 'id_subgroups');
    }
}
