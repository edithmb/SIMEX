<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Paso de seguimiento (hito) posible dentro de una operación logística.
 *
 * Ej.: "Recogida en origen", "Zarpe", "Arribo", "Despacho aduanero".
 * Se combina con un Incoterm para generar el plan de tracking real.
 *
 * @property int    $id
 * @property string $name Nombre del hito.
 */
class TrackingStep extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = ['name'];

    /**
     * Pasos Incoterm concretos que referencian este hito.
     *
     * @return HasMany
     */
    public function incoterms(): HasMany
    {
        return $this->hasMany(Incoterm::class);
    }
}
