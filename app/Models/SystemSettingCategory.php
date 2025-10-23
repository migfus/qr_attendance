<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property integer $id
 * @property integer $sort_id
 * @property string $name
 * @property string $icon
 * @property string $description
 * @property string $href
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 *
 * @method HasMany system_settings()
 */

class SystemSettingCategory extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'icon', 'sort_id', 'description', 'href'];

    public function system_settings() : HasMany {
        return $this->hasMany(SystemSettings::class);
    }
}
