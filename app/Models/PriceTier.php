<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PriceTier extends Model
{
    // Model for tbl_pricetiers
    protected $table = 'tbl_pricetiers';
    protected $primaryKey = 'priceTierID';
    public $timestamps = false; // migration defines dateCreated only

    protected $fillable = [
        'pricetier',
    ];
}
