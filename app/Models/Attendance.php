<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\{BelongsTo, HasMany};

/**
 * @property string  $id
 * @property string  $author_id
 * @property integer $department_id
 * @property string  $name
 * @property \Carbon\Carbon  $start_date_time
 * @property \Carbon\Carbon  $end_date_time
 * @property \Carbon\Carbon  $forced_closed_date_time
 * @property string  $description
 * @property \Carbon\Carbon  $created_at
 * @property \Carbon\Carbon  $updated_at
 *
 * @method BelongsTo author()
 * @method HasMany attendees()
 * @method HasMany attendees_participated_only()
 * */

class Attendance extends Model
{
    use HasUlids;

    public function author() : BelongsTo {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function attendees() : HasMany {
        return $this->hasMany(Attendee::class);
    }

    public function attendees_participated_only() : HasMany {
        return $this->hasMany(Attendee::class)->whereNull('scanner_user_id');
    }
}
