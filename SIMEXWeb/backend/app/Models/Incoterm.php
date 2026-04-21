<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Paso concreto de un Incoterm: asocia un `IncotermType` con un `TrackingStep`
 * y una `responsability` (vendedor/comprador) ordenado por `order_num`.
 *
 * Se usa para construir dinámicamente la lista de hitos de seguimiento de una
 * operación logística (ver `LogisticsOperation::getIncotermStepsAttribute()`).
 *
 * @property int    $id
 * @property int    $incoterm_type_id FK a `incoterm_types`.
 * @property int    $tracking_step_id FK a `tracking_steps`.
 * @property int    $order_num        Orden del paso dentro del Incoterm.
 * @property string $responsability   'vendedor' | 'comprador' según recaiga la responsabilidad.
 */
class Incoterm extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = ['incoterm_type_id', 'tracking_step_id', 'order_num', 'responsability'];

    /**
     * Tipo Incoterm (EXW, FOB, CIF…).
     *
     * @return BelongsTo
     */
    public function incotermType(): BelongsTo
    {
        return $this->belongsTo(IncotermType::class);
    }

    /**
     * Paso de tracking asociado a este Incoterm.
     *
     * @return BelongsTo
     */
    public function trackingStep(): BelongsTo
    {
        return $this->belongsTo(TrackingStep::class);
    }

    /**
     * Ofertas comerciales que aplican este Incoterm.
     *
     * @return HasMany
     */
    public function commercialOffers(): HasMany
    {
        return $this->hasMany(CommercialOffer::class);
    }
}
