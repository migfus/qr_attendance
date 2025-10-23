<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property string $id
 * @property string $content
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 */

class HeroIcon extends Model
{
  use HasFactory;

  // public $primaryKey = 'name';

  protected $fillable = ['name', 'content'];
}
