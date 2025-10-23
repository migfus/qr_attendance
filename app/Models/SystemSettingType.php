<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $id
 * @property string  $name
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 */

class SystemSettingType extends Model
{
    use HasFactory;
}
