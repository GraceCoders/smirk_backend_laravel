<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserEthnicity extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'ethnicity_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function ethnicity()
    {
        return $this->belongsTo(Ethnicity::class);
    }
}