<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\{BelongsTo, BelongsToMany, HasOne};
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laratrust\Contracts\LaratrustUser;
use Laratrust\Traits\HasRolesAndPermissions;
use Laravel\Sanctum\HasApiTokens;

/**
 * @property string  $id
 * @property integer $department_id
 * @property string  $name
 * @property string  $email
 * @property \Carbon\Carbon $email_verified_at
 * @property string  $password
 * @property string  $avatar_url
 * @property string  $recovery_code
 * @property string  $remember_token
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 *
 * @method BelongsTo department()
 * @method HasOne quick_response()
 */

class User extends Authenticatable implements LaratrustUser {
    use HasFactory, Notifiable, HasRolesAndPermissions, HasUlids, HasApiTokens;

    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'name',
        'email',
        'avatar_url',
        'cover',
        'password',
        'recovery_code',
        'department_id'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function department(): BelongsTo {
        return $this->belongsTo(Department::class);
    }

    public function quick_response(): HasOne {
        return $this->hasOne(QuickResponse::class)->limit(1)->orderBy('created_at', 'ASC');
    }

    public function role(): BelongsToMany {
        return $this->belongsToMany(Role::class)->limit(1)->orderBy('created_at', 'ASC')->select('name', 'id');
    }

    public function user_settings(): HasOne {
        return $this->hasOne(UserSettings::class);
    }
}
