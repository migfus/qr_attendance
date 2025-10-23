<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Laratrust\Models\Role as RoleModel;

/**
 * @property string $id
 * @property string $name
 * @property string $icon_name
 * @property string $display_name
 * @property string $description
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 *
 * @method void attachPermission()
 * @method void detachPermission()
 * @method BelongsTo hero_icon()
 */

class Role extends RoleModel {
    use HasUlids;

    public $fillable = [
        'id',
        'name',
        'icon_name',
        'display_name',
        'description'
    ];

    public function attachPermission($permissionId) {
        return $this->permissions()->attach($permissionId);
    }

    public function detachPermission($permissionId) {
        return $this->permissions()->detach($permissionId);
    }

    public function hero_icon(): BelongsTo {
        return $this->belongsTo(HeroIcon::class, 'icon_name', 'name');
    }
}
