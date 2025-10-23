<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property string  $id
 * @property string $user_id
 * @property string $config
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 */

class UserSettings extends Model
{
    use HasFactory;

    protected $guarded = [];
}
