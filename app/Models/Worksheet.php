<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Worksheet extends Model
{
    protected $primaryKey = 'id_worksheets';

    /**
     * Группы, принадлежащие учебному листу
     */
    public function groups()
    {
        return $this->hasMany(Group::class, 'id_worksheets', 'id_worksheets');
    }
}
