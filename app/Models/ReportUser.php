<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReportUser extends Model
{
    use HasFactory;
    protected $table = 'report_users';
    protected $fillable = [
        'user_id',
        'report_by',
        'status',
        'report'
    ];
    public function user(){
    return $this->belongsTo(User::class);
        
    }
}
