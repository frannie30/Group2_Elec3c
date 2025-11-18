<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EsBookmark extends Model
{
    use HasFactory;

    protected $table = 'tbl_esbookmarks';
    protected $primaryKey = 'esBookmarkID';
    public $timestamps = false;

    protected $fillable = [
        'userID',
        'ecospaceID',
        'dateCreated',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'userID', 'id');
    }

    public function ecospace()
    {
        return $this->belongsTo(Ecospace::class, 'ecospaceID', 'ecospaceID');
    }
}
