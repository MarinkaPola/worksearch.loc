<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Vacancy extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
        'vacancy_name',
        'workers_amount',
        'organization_id',
        'salary',
        'employer_id'
    ];

    public function employer()
    {
        return $this->belongsTo(User::class, 'employer_id');

    }

    public function organization()
    {
        return $this-> belongsTo(Organization::class, 'organization_id');
    }

    public function vacancyUsers(): BelongsToMany
    {
        return $this->belongsToMany(User::class)->withTimestamps();
    }

}
