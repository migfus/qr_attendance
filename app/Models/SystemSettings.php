<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property integer $id
 * @property integer $sort_id
 * @property integer $system_setting_category_id
 * @property integer $system_setting_type_id
 * @property string $name
 * @property string $description
 * @property string $value
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 *
 * @method BelongsTo system_setting_type()
 */

class SystemSettings extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description', 'value', 'sort_id', 'system_setting_type_id'];

    public function system_setting_type() : BelongsTo {
        return $this->belongsTo(SystemSettingType::class);
    }
}
