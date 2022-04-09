<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class ChatUser extends Model
{
    use HasFactory;
    protected $table = 'chat_user';
    protected $fillable = [
        'sender_id',
        'receiver_id',  
         'status'   
    ];
    public function user()
    {
        return $this->belongsTo(User::class,'sender_id','id')->where('id','!=',Auth::id());
    }
    public function sender()
    {
        return $this->belongsTo(User::class,'receiver_id','id')->where('id','!=',Auth::id());
    }
}
