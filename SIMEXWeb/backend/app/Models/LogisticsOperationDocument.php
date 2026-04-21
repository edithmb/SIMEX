<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Documento concreto asociado a una `LogisticsOperation`.
 *
 * `is_ad_hoc` marca documentos añadidos fuera de los tipos estándar
 * (con `custom_name` como etiqueta libre). Sólo registra `created_at`;
 * `UPDATED_AT` está desactivado.
 *
 * @property int         $id
 * @property int         $logistics_operation_id FK a `logistics_operations`.
 * @property int|null    $document_type_id       FK a `document_types` (null si es ad-hoc).
 * @property string      $file_url
 * @property string      $file_name
 * @property string      $status                 Estado del documento (pendiente/subido/…).
 * @property bool        $is_ad_hoc              Documento personalizado fuera del catálogo.
 * @property string|null $custom_name            Nombre libre si `is_ad_hoc` = true.
 * @property \Illuminate\Support\Carbon|null $uploaded_at
 */
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

    /**
     * Casts a tipos PHP nativos.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'is_ad_hoc'   => 'boolean',
            'uploaded_at' => 'datetime',
        ];
    }

    /**
     * Operación logística a la que pertenece el documento.
     *
     * @return BelongsTo
     */
    public function logisticsOperation(): BelongsTo
    {
        return $this->belongsTo(LogisticsOperation::class);
    }

    /**
     * Tipo catalogado del documento (null si es ad-hoc).
     *
     * @return BelongsTo
     */
    public function documentType(): BelongsTo
    {
        return $this->belongsTo(DocumentType::class);
    }
}
