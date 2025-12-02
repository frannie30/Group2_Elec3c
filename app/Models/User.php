<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Ecospace;
use App\Models\EsBookmark;
use App\Models\EvBookmark;

class User extends Authenticatable
{
    use HasApiTokens;

    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory;
    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'profile_photo_url',
    ];

    /**
     * The attributes that should be cast to native types.
     * Added userTypeID as integer to avoid type mismatches when comparing.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'userTypeID' => 'integer',
        'deleted_at' => 'datetime',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function userType()
    {
        return $this->belongsTo(UserType::class, 'userTypeID', 'userTypeID');
    }

    public function ecospaces()
    {
        return $this->hasMany(Ecospace::class, 'userID', 'id');
    }

    public function esBookmarks()
    {
        return $this->hasMany(EsBookmark::class, 'userID', 'id');
    }

    public function evBookmarks()
    {
        return $this->hasMany(EvBookmark::class, 'userID', 'id');
    }

    /**
     * Events created by this user (owner relationship).
     */
    public function events()
    {
        return $this->hasMany(\App\Models\Event::class, 'userID', 'id');
    }
}
