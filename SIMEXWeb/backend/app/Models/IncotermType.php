<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Tipo de Incoterm estandarizado (EXW, FOB, CIF, DAP…).
 *
 * Agrupa los pasos concretos (`Incoterm`) que componen cada tipo.
 *
 * @property int    $id
 * @property string $code Código ISO del Incoterm.
 * @property string $name Nombre legible.
 */
class IncotermType extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = ['code', 'name'];

    /**
     * Pasos concretos que componen este tipo Incoterm.
     *
     * @return HasMany
     */
    public function incoterms(): HasMany
    {
        return $this->hasMany(Incoterm::class);
    }
}
