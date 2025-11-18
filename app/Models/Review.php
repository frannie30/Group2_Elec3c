<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Schema;

class Review extends Model
{
    use HasFactory;
    protected $table = 'tbl_reviews';
    protected $primaryKey = 'reviewID';
    public $timestamps = false;

    protected $fillable = [
        'userID',
        'ecospaceID',
        'eventID',
        'rating',
        'review',
        'dateCreated',
        'dateUpdated',
    ];

    protected $casts = [
        'rating' => 'float',
        'dateCreated' => 'datetime',
        'dateUpdated' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'userID', 'id');
    }

    public function ecospace()
    {
        return $this->belongsTo(Ecospace::class, 'ecospaceID', 'ecospaceID');
    }

    public function event()
    {
        // If the `eventID` column was dropped from `tbl_reviews`, avoid building a belongsTo
        // that references the missing column. Return a safe no-op relation instead.
        if (!Schema::hasColumn($this->getTable(), 'eventID')) {
            return $this->belongsTo(Event::class, 'id', 'id')->whereRaw('0 = 1');
        }

        return $this->belongsTo(Event::class, 'eventID', 'eventID');
    }

    public function images()
    {
        return $this->hasMany(ReviewImage::class, 'reviewID', 'reviewID');
    }
}
