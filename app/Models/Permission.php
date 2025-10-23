<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Laratrust\Models\Permission as PermissionModel;

/**
 * @property string $id
 * @property string $name
 * @property string $display_name
 * @property string $description
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 */

class Permission extends PermissionModel {
    use HasUlids;
    public $guarded = [];

    protected $fillable = [
        'name',
        'display_name',
        'description',
        'permission_module_id',
        'permission_type_id',
    ];

    public function permission_type() {
        return $this->belongsTo(PermissionType::class);
    }

    public function permission_module() {
        return $this->belongsTo(PermissionModule::class);
    }
}
