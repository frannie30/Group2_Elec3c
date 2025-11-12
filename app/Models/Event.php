<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Event extends Model
{
    use SoftDeletes;

    // Map to the tbl_events table created by the project's migration
    protected $table = 'tbl_events';
    protected $primaryKey = 'eventID';
    public $timestamps = false; // dateCreated/dateUpdated are handled by DB defaults

    protected $fillable = [
        'eventName',
        'eventTypeID',
        'userID',
        'eventAdd',
        'statusID',
        'priceTierID',
        'eventDate',
        'eventDesc',
        'isDone',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'userID', 'id');
    }

    public function images()
    {
        return $this->hasMany(EventImage::class, 'eventID', 'eventID');
    }

    public function priceTier()
    {
        return $this->belongsTo(PriceTier::class, 'priceTierID', 'priceTierID');
    }

    public function eventType()
    {
        return $this->belongsTo(EventType::class, 'eventTypeID', 'eventTypeID');
    }

    public function status()
    {
        return $this->belongsTo(\App\Models\Status::class, 'statusID', 'statusID');
    }
}
