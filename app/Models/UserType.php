<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserType extends Model
{
    protected $table = 'usertype';
    protected $primaryKey = 'userTypeID';
    public $incrementing = false;
    protected $keyType = 'string';

    public function users()
    {
        return $this->hasMany(User::class, 'userTypeID', 'userTypeID');
    }
}
