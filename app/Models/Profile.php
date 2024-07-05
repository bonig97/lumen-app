<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Profile extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['name', 'surname', 'phone'];

    public function attributes(): HasMany
    {
        return $this->hasMany(ProfileAttribute::class);
    }
}
