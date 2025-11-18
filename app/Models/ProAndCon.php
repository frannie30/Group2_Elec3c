<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProAndCon extends Model
{
    protected $table = 'tbl_prosandcons';
    protected $primaryKey = 'pcID';
    public $incrementing = true;
    protected $keyType = 'int';

    const CREATED_AT = 'dateCreated';
    const UPDATED_AT = 'dateUpdated';

    protected $fillable = [
        'isPro',
        'description',
        'userID',
        'ecospaceID',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'userID');
    }

    public function ecospace()
    {
        return $this->belongsTo(Ecospace::class, 'ecospaceID', 'ecospaceID');
    }
}
