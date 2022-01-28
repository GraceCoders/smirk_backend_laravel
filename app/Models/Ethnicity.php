<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ethnicity extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'status',
    ];

    public function ethnicity()
    {
        return $this->hasOne(UserEthnicity::class);
    }
}