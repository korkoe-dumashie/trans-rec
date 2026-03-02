<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserSession extends Model
{
    protected $table = 'user_sessions';
    protected $fillable = [
        'user_id',
        'last_login_time',
    ];



    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
