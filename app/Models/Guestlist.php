<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Guestlist extends Model
{
    use HasFactory;

    protected $table = 'tbl_guestlists';
    protected $primaryKey = 'guestID';
    public $timestamps = false; // custom timestamp columns are used

    protected $fillable = [
        'eventID',
        'userID',
        'isGoing',
        'dateCreated',
        'dateUpdated',
    ];

    protected $casts = [
        'isGoing' => 'boolean',
    ];

    public function event()
    {
        return $this->belongsTo(Event::class, 'eventID', 'eventID');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'userID', 'id');
    }
}
