<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProfileAttribute extends Model
{
    use SoftDeletes;

    protected $fillable = ['profile_id', 'attribute'];

    public function profile(): BelongsTo
    {
        return $this->belongsTo(Profile::class);
    }
}
