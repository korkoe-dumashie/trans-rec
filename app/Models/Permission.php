<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    protected $fillable = [
        'role_id',
        'resource_id',
        'can_create',
        'can_read',
        'can_update',
        'can_delete',
        'can_export',
        'can_import',

    ];

    protected $casts = [
        'can_create' => 'boolean',
        'can_read' => 'boolean',
        'can_update' => 'boolean',
        'can_delete' => 'boolean',
        'can_export' => 'boolean',
        'can_import' => 'boolean',
    ];

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function resource()
    {
        return $this->belongsTo(Resource::class);
    }
}
