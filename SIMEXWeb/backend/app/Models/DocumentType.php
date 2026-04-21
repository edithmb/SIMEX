<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Tipo de documento soportado por el sistema (p.ej. B/L, factura, packing list).
 *
 * Permite activar/desactivar tipos sin perder el histórico mediante `is_active`.
 *
 * @property int         $id
 * @property string      $code        Código corto del tipo.
 * @property string      $name        Nombre legible.
 * @property string|null $description
 * @property bool        $is_active   Si el tipo está disponible para nuevos documentos.
 */
class DocumentType extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'code',
        'name',
        'description',
        'is_active',
    ];

    /**
     * Casts a tipos PHP nativos.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }
}
