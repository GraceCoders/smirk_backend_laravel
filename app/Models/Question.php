<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;
    protected $table = 'have_questions';
    protected $fillable = [
        'user_id',
        'question',
        'status'
    ];
}
