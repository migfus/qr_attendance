<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\QuickResponse;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;

/**
 * @property string $last_name
 * @property string $first_name
 * @property string $mid_name
 * @property integer $department_id
 * @property integer $position_id
 * @property integer $employee_status_id
 * @property integer $claim_type_id
 * @property string $email
 * @property string $password
 * @property \Carbon\Carbon  $created_at
 * @property \Carbon\Carbon  $updated_at
 *
 * @method HasMany quickResponseCode()
 * @method MorphMany files()
 * @method BelongsTo extraName()
 * @method BelongsTo position()
 * @method BelongsTo department()
 * @method BelongsTo employeeStatus()
 * @method HasMany requestStatuses()
 * @method HasOne latestRequestStatus()
 */

class Employee extends Model {
    use HasUlids, Notifiable, HasFactory;

    protected $fillable = [
        'last_name',
        'first_name',
        'mid_name',
        'extra_name_id',
        'department_id',
        'position_id',
        'employee_status_id',
        'claim_type_id',
        'email',
        'password'
    ];

    public function routeNotificationForMail() {
        return $this->email;
    }

    public function quickResponseCodes(): HasMany {
        return $this->hasMany(QuickResponse::class)->select(['id', 'employee_id']);
    }

    public function files(): MorphMany {
        return $this->morphMany(File::class, 'fileable')->select(['fileable_id', 'file_url', 'file_type_id', 'size', 'width', 'height', 'thumbnail_url', 'id']);
    }

    public function extraName(): BelongsTo {
        return $this->belongsTo(ExtraName::class)->select(['id', 'name']);
    }

    public function position(): BelongsTo {
        return $this->belongsTo(Position::class)->select(['id', 'name']);
    }

    public function department(): BelongsTo {
        return $this->belongsTo(Department::class)->select(['id', 'name', 'short_name']);
    }

    public function employeeStatus(): BelongsTo {
        return $this->belongsTo(EmployeeStatus::class)->select(['id', 'name']);
    }

    // public function requestStatus(): HasMany {
    //     return $this->hasMany(RequestStatus::class);
    // }

    public function claimType(): belongsTo {
        return $this->belongsTo(ClaimType::class);
    }

    public function requestStatuses(): HasMany {
        return $this->hasMany(RequestStatus::class)->orderBy('id', 'DESC');
    }

    public function latestRequestStatus(): HasOne {
        return $this->hasOne(RequestStatus::class)->latestOfMany();
    }
}
