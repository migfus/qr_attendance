<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property integer $id
 * @property string $name
 * @property integer $request_status_type_id
 * @property string $employee_id
 * @property string $content
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 *
 * @method HasMany employees()
 * @method BelongsTo requestStatusType()
 * @method BelongsTo user()
 */


class RequestStatus extends Model {

    protected $fillable = ['request_status_type_id', 'employee_id', 'user_id', 'content'];

    public function employees(): HasMany {
        return $this->hasMany(Employee::class);
    }

    public function requestStatusType(): BelongsTo {
        return $this->belongsTo(RequestStatusType::class);
    }

    public function user(): BelongsTo {
        return $this->belongsTo(User::class);
    }
}
