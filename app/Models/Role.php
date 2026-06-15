<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Role extends Model
{
    protected $primaryKey = 'id_roles';

    /**
     * Пользователи принадлежащие роли через таблицу user_role
     */
    public function users()
    {
        return $this->belongsToMany(User::class, 'user_role', 'id_roles', 'id_users', 'id_roles', 'id_users');
    }
}
