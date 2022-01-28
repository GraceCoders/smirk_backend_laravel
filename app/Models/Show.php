<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Show extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'show_icon',
        'status',
    ];

    public function show()
    {
        return $this->hasOne(UserShow::class);
    }
}