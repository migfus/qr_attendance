<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

/**
 * @property string $id
 * @property string $employee_id
 * @property \Carbon\Carbon $deleted_at
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 *
 * @method BelongsTo employee()
 */

class QuickResponse extends Model
{
    use SoftDeletes;

    public $incrementing = false;
    protected $keyType = 'string';
    protected $fillable = [
        'employee_id',
    ];

    protected static function boot() : void {
        parent::boot();

        static::creating(function ($model) {
            do {
                $unique_id = strtoupper(Str::random(4) . Str::random(4));
            }
            while (self::where('id', $unique_id)->exists());

            $model->id = $unique_id;
        });
    }

    public function employee() : BelongsTo {
        return $this->belongsTo(Employee::class);
    }
}
