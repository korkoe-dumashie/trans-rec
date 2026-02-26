<?php

namespace App\Models;


use Illuminate\Foundation\Auth\User as Authenticatable;

use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class Auth extends Authenticatable
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


    public function userDetails(){
        //helper method to fetch user details, roles for the client

        return [
            'id' => $this->user->id,
            'first_name' => $this->user->first_name,
            'last_name' => $this->user->last_name,
            'staff_id' => $this->staff_id,
            'role' => $this->user->role,
        ];
    }

    public function user(){
        return $this->belongsTo(User::class);
    }
}
