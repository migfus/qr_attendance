<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PermissionModule extends Model {
    protected $table = 'permission_modules';

    protected $fillable = ['name'];

    public function permissions(): HasMany {
        return $this->hasMany(Permission::class);
    }
}
