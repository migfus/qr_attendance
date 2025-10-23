<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $id
 * @property string $name
 * @property string $image_url
 * @property integer $quantity
 * @property string $user_id
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 */

class Inventory extends Model {
    protected $fillable = ['name', 'image_url', 'quantity', 'user_id'];
}
