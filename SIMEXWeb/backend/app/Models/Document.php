<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Documento genérico polimórfico asociado a cualquier entidad del sistema.
 *
 * Se relaciona con la entidad destino mediante el par `(entity_type, entity_id)`
 * (relación morph manual). Soporta cifrado opcional en reposo indicado por
 * `is_encrypted` y la clave en `encryption_key`. Sólo se registra la marca
 * `created_at`; `UPDATED_AT` está desactivado.
 *
 * @property int         $id
 * @property int         $document_type_id FK a `document_types`.
 * @property string      $entity_type      Clase/tipo de la entidad propietaria.
 * @property int         $entity_id        ID de la entidad propietaria.
 * @property string      $file_name        Nombre visible del archivo.
 * @property string      $file_path        Ruta de almacenamiento.
 * @property int         $file_size_bytes  Tamaño en bytes.
 * @property string|null $mime_type
 * @property bool        $is_encrypted     Si el archivo se almacena cifrado.
 * @property string|null $encryption_key   Clave usada para cifrar (si aplica).
 * @property int         $uploaded_by      FK al usuario que lo subió.
 */
class Document extends Model
{
    use HasFactory;

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

    /**
     * Casts a tipos PHP nativos.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'is_encrypted' => 'boolean',
            'file_size_bytes' => 'integer',
            'created_at' => 'datetime',
        ];
    }

    /**
     * Usuario que subió el documento.
     *
     * @return BelongsTo
     */
    public function uploader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }
}
