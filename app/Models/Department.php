<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 *  @property integer $id
 *  @property string  $name
 *  @property string  $short_name
 *  @property string  $image_url
 *  @property string  $email
 *  @property integer  $phone_number
 *  @property \Carbon\Carbon  $created_at
 *  @property \Carbon\Carbon  $updated_at
 *
 *  @method HasMany guestQrCode()
 * */

class Department extends Model {
    protected $fillable = ['name', 'image_url', 'short_name', 'email', 'phone_number'];

    public function guestQrCode(): HasMany {
        return $this->hasMany(GuestQrCode::class, 'department_id');
    }

    public function employees(): HasMany {
        return $this->hasMany(Employee::class, 'department_id');
    }
}
