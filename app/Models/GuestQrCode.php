<?php

namespace App\Models;

use App\Notifications\NewUserNotification;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Str;

/**
 * @property string  $id
 * @property string $guest_id
 * @property string $name
 * @property integer $department_id
 * @property integer $status_id
 * @property \Carbon\Carbon $deleted_at
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 *
 * @method BelongsTo department()
 * @method BelongsTo status()
 */

class GuestQrCode extends Model {
    use SoftDeletes;

    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';
    protected $fillable = [
        'id',
        'name',
        'status_id',
        'department_id',
        'guest_id',
    ];

    // protected static function boot() : void {
    //     parent::boot();

    //     static::creating(function ($model) {
    //         do {
    //             $unique_id = strtoupper(Str::random(4) . Str::random(4));
    //         }
    //         while (self::where('id', $unique_id)->exists());

    //         $model->id = $unique_id;

    //         $allStaff = User::whereHas('roles', function ($query) {
    //             $query->whereIn('name', ['admin', 'manager', 'scanner']);
    //         })->get();

    //         Notification::send($allStaff, new NewUserNotification($model));

    //     });
    // }

    public function department(): BelongsTo {
        return $this->belongsTo(Department::class);
    }

    public function status(): BelongsTo {
        return $this->belongsTo(EmployeeStatus::class);
    }
}
