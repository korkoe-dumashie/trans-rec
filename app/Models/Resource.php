<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Resource extends Model
{
    protected $table = 'resources';

    protected $fillable = [
        'name',
        'description',
    ];

    public function permissions(){
        return $this->hasMany(Permission::class);
    }
}
