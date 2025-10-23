<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property integer $id
 * @property string $name
 * @property \Carbon\Carbon  $created_at
 * @property \Carbon\Carbon  $updated_at
 *
 * @method HasMany employees()
 */

class ExtraName extends Model {

    protected $fillable = ['name'];

    public function employees(): HasMany {
        return $this->hasMany(Employee::class);
    }
}
