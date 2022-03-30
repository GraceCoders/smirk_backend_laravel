<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BlockUser extends Model
{
    use HasFactory;
    protected $table = 'block_user';
    protected $fillable = [
        'user_id',
        'blocked_by',
        'status'
    ];
    public function user(){
    return $this->belongsTo(User::class);
        
    }
}
