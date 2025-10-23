<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;

/**
 * @property string $id
 * @property string $guest_id
 * @property string $qr_link
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 */

class SavedQuickResponse extends Model
{
    use HasUlids;

    protected $fillable = ['guest_id', 'qr_link'];
}
