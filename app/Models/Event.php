<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Guestlist;
use App\Models\Review;
use App\Models\Status;

class Event extends Model
{
    use SoftDeletes, HasFactory;

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
        'isFinished',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'userID', 'id');
    }

    public function images()
    {
        return $this->hasMany(EventImage::class, 'eventID', 'eventID');
    }

    public function guestlists()
    {
        return $this->hasMany(Guestlist::class, 'eventID', 'eventID');
    }

    /**
     * Attendees relation (only guestlist rows where isGoing = 1)
     */
    public function attendees()
    {
        return $this->hasMany(Guestlist::class, 'eventID', 'eventID')->where('isGoing', true);
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
        return $this->belongsTo(Status::class, 'statusID', 'statusID');
    }

    public function reviews()
    {
        return $this->hasMany(Review::class, 'eventID', 'eventID');
    }
}
