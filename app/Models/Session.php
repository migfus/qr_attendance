<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property string $id
 * @property string $user_id
 * @property string $ip_address
 * @property string $user_agent
 * @property string $payload
 * @property integer $last_activity
 *
 * @method BelongsTo user()
 */

class Session extends Model {

    public function user(): BelongsTo {
        return $this->belongsTo(User::class);
    }
}
