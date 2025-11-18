<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReviewImage extends Model
{
    protected $table = 'tbl_reviewimages';
    protected $primaryKey = 'revImgID';
    public $timestamps = false;

    protected $fillable = [
        'revImgName',
        'reviewID',
        'dateCreated',
    ];

    protected $casts = [
        'dateCreated' => 'datetime',
    ];

    public function review()
    {
        return $this->belongsTo(Review::class, 'reviewID', 'reviewID');
    }
}
