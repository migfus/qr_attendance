<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * @property integer $id
 * @property string $name
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 *
 * @method BelongsTo heroIcon()
 * @method BelongsTo latestRequestStatus()
 */

class RequestStatusType extends Model {
    public function heroIcon(): BelongsTo {
        return $this->belongsTo(HeroIcon::class, 'hero_icon_id', 'name');
    }

    public function latestRequestStatus(): HasOne {
        return $this->hasOne(RequestStatus::class)->latest();
    }
}
