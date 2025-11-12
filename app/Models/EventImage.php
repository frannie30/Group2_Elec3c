<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EventImage extends Model
{
    protected $table = 'tbl_eventImages';
    protected $primaryKey = 'eventImageID';
    public $timestamps = true;

    protected $fillable = ['eventID', 'path', 'order', 'caption'];

    public function event()
    {
        return $this->belongsTo(Event::class, 'eventID', 'eventID');
    }
}
