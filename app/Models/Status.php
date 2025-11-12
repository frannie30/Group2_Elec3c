<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Status extends Model
{
    // Model for tbl_statuses
    protected $table = 'tbl_statuses';
    protected $primaryKey = 'statusID';
    public $timestamps = false; // migration only defines dateCreated

    protected $fillable = [
        'status',
    ];
}
