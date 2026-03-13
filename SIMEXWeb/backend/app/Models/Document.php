<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Document extends Model
{
    const UPDATED_AT = null;

    protected $fillable = [
        'document_type_id',
        'entity_type',
        'entity_id',
        'file_name',
        'file_path',
        'file_size_bytes',
        'mime_type',
        'is_encrypted',
        'encryption_key',
        'uploaded_by',
    ];

    protected function casts(): array
    {
        return [
            'is_encrypted' => 'boolean',
            'file_size_bytes' => 'integer',
            'created_at' => 'datetime',
        ];
    }

    public function uploader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }
}
