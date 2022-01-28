<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Preference extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'status',
    ];

    public function preference()
    {
        return $this->hasOne(UserPreference::class);
    }
}