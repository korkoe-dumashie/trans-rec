<?php

namespace App\Models;


use Illuminate\Foundation\Auth\User as Authenticatable;

use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class Auth extends Model
{
    use HasApiTokens;

    protected  $table = 'auths';
    protected  $fillable = [
        'user_id',
        'staff_id',
        'password',
        'reset_password',
    ];

    protected $casts = [
        'reset_password' => 'boolean',
    ];

    protected $hidden = [
        'password',
    ];




    public function user(){
        return $this->belongsTo(User::class);
    }
}
