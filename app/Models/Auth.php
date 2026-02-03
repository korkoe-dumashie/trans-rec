<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Auth extends Model
{


    protected  $table = 'auths';
    protected  $fillable = [
        'user_id',
        'staff_id',
        'password',
        'reset_password',
    ];


    public function user(){
        return $this->belongsTo(User::class);
    }
}
