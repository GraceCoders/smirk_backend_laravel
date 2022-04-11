<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GetMatch extends Model
{
    use HasFactory;
    protected $table = 'get_match';
    protected $fillable = [
        'user_id',
        'match_with',
        'status'
    ];
}
