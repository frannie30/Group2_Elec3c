<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Review;
use App\Models\ProAndCon;

class Ecospace extends Model
{
    use SoftDeletes, HasFactory;

    // Table and primary key
    protected $table = 'ecospaces';
    protected $primaryKey = 'ecospaceID';
    public $incrementing = true;
    protected $keyType = 'int';

    // Map Laravel timestamps to custom DB column names
    const CREATED_AT = 'dateCreated';
    const UPDATED_AT = 'dateUpdated';

    // Fillable fields matching migration
    protected $fillable = [
        'ecospaceName',
        'ecospaceAdd',
        'ecospaceDesc',
        'userID',
        'statusID',
        'priceTierID',
        'openingHours',
        'closingHours',
        'daysOpened',
    ];

    // Casts (basic)
    protected $casts = [
        'ecospaceName' => 'string',
        'ecospaceAdd'  => 'string',
        'ecospaceDesc' => 'string',
        'userID'       => 'integer',
        'statusID'     => 'integer',
        'priceTierID'  => 'integer',
        'daysOpened'   => 'string',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class, 'userID');
    }

    public function status()
    {
        return $this->belongsTo(Status::class, 'statusID');
    }

    public function priceTier()
    {
        return $this->belongsTo(PriceTier::class, 'priceTierID');
    }

    public function images()
    {
        // Explicit foreign and local keys: tbl_esImages.ecospaceID -> ecospaces.ecospaceID
        return $this->hasMany(Image::class, 'ecospaceID', 'ecospaceID')->orderBy('order');
    }

    public function reviews()
    {
        return $this->hasMany(Review::class, 'ecospaceID', 'ecospaceID');
    }

    public function prosAndCons()
    {
        return $this->hasMany(ProAndCon::class, 'ecospaceID', 'ecospaceID')->orderByDesc('dateCreated');
    }
}
