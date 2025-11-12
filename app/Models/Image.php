<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    // Map to the ecospace images table requested by the user
    protected $table = 'tbl_esImages';
    protected $primaryKey = 'esImageID';
    public $timestamps = true;

    protected $fillable = ['ecospaceID', 'path', 'order', 'caption'];

    public function ecospace()
    {
        return $this->belongsTo(Ecospace::class, 'ecospaceID', 'ecospaceID');
    }
}
