<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * @property integer $id
 * @property string $file_url
 * @property \Carbon\Carbon  $created_at
 * @property \Carbon\Carbon  $updated_at
 *
 * @method HasMany fileable()
 */

class File extends Model {
    protected $fillable = ['file_url', 'file_type_id', 'size', 'width', 'height', 'thumbnail_url'];

    public function fileable(): MorphTo {
        return $this->morphTo();
    }

    public function fileType(): BelongsTo {
        return $this->belongsTo(FileType::class);
    }
}
