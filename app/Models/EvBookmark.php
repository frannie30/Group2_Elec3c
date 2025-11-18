<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EvBookmark extends Model
{
    use HasFactory;

    protected $table = 'tbl_evbookmarks';
    protected $primaryKey = 'evBookmarkID';
    public $timestamps = false;

    protected $fillable = [
        'userID',
        'eventID',
        'dateCreated',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'userID', 'id');
    }

    public function event()
    {
        return $this->belongsTo(Event::class, 'eventID', 'eventID');
    }
}
