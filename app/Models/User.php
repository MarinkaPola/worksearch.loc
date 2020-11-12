<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use  HasApiTokens, Notifiable, SoftDeletes, HasFactory;


    const ROLE_WORKER = 'worker';
    const ROLE_EMPLOYER = 'employer';
    const ROLE_ADMIN = 'admin';


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'password',
        'role',
        'phone',
        'country',
        'city'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     *
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    const ROLES = [
        self::ROLE_WORKER,
        self::ROLE_EMPLOYER,
        self::ROLE_ADMIN
    ];


    /**
     * @param string $value
     */
    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = Hash::make($value);
    }

    public function organizations(): HasMany
    {
        return $this->hasMany(Organization::class, 'employer_id');
    }

    public function vacancies(): HasMany
    {
        return $this->hasMany(Vacancy::class, 'employer_id');
    }

    public function userVacancies(): BelongsToMany
    {
        return $this->belongsToMany(Vacancy::class)->withTimestamps();
    }

}
