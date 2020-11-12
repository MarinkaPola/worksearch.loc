<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Organization extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
        'title',
        'country',
        'city',
        'employer_id'
    ];
    public function employer()
    {
        return $this-> belongsTo(User::class, 'employer_id');
    }
    public function vacancies(): HasMany
    {
        return $this->hasMany(Vacancy::class, 'organization_id');
    }
}
