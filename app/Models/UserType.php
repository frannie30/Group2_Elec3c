<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserType extends Model
{
    public function users()
    {
        return $this->hasMany(User::class, 'userTypeID', 'userTypeID');
    }
}
