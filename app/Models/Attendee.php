<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property integer $id
 * @property string $user_id
 * @property string $host_user_id
 * @property string $scanner_user_id
 * @property string $attendance_id
 * @property \Carbon\Carbon $scanned_date_time
 * @property \Carbon\Carbon  $created_at
 * @property \Carbon\Carbon  $updated_at
 *
 * @method BelongsTo user()
 * @method BelongsTo attendance()
 * @method BelongsTo scanner()
 */

class Attendee extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'scanned_date_time', 'scanner_user_id'];

    public function user(): BelongsTo { // 1
        return $this->belongsTo(User::class);
    }

    public function attendance(): BelongsTo { // 1
        return $this->belongsTo(Attendance::class);
    }

    public function scanner(): BelongsTo {
        return $this->belongsTo(User::class, 'scanner_user_id', 'id');
    }
}
