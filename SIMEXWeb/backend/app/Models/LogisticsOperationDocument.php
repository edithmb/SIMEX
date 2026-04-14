<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LogisticsOperationDocument extends Model
{
    use HasFactory;

    const UPDATED_AT = null;

    protected $fillable = [
        'logistics_operation_id',
        'document_type_id',
        'file_url',
        'file_name',
        'status',
        'is_ad_hoc',
        'custom_name',
        'uploaded_at',
    ];

    protected function casts(): array
    {
        return [
            'is_ad_hoc'   => 'boolean',
            'uploaded_at' => 'datetime',
        ];
    }

    public function logisticsOperation(): BelongsTo
    {
        return $this->belongsTo(LogisticsOperation::class);
    }

    public function documentType(): BelongsTo
    {
        return $this->belongsTo(DocumentType::class);
    }
}
