<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EventType extends Model
{
    // Map to the project's table name
    protected $table = 'tbl_eventtypes';
    protected $primaryKey = 'eventTypeID';
    public $timestamps = false;

    protected $fillable = ['eventTypeName'];
}
