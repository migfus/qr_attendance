<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;

/**
 * @property string  $id
 * @property integer $feedback_type_id
 * @property string  $title
 * @property string  $content
 * @property \Carbon\Carbon  $created_at
 * @property \Carbon\Carbon  $updated_at
 */

class Feedback extends Model
{
    use HasUlids;

    protected $fillable = ['feedback_type_id', 'content'];
}
