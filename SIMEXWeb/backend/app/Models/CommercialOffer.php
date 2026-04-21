<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * Oferta comercial (presupuesto) emitida en respuesta a una `ClientRequest`.
 *
 * Máquina de estados: `draft` → `accepted` | `rejected`. Cuando pasa a
 * `accepted` se crea la `LogisticsOperation` asociada (relación 1:1).
 *
 * @property int         $id
 * @property string      $reference           Referencia legible de la oferta.
 * @property int         $client_request_id   FK a la solicitud origen.
 * @property int         $client_id
 * @property int         $incoterm_id
 * @property int         $origin_port_id      FK a `ports`.
 * @property int         $destination_port_id FK a `ports`.
 * @property int         $container_type_id
 * @property string      $price               Precio ofertado (decimal 2).
 * @property string|null $valid_until         Fecha límite de validez.
 * @property string      $status              Estado: draft|accepted|rejected.
 * @property string|null $rejection_reason
 * @property string|null $comments
 * @property int|null    $odoo_id
 * @property int|null    $created_by
 * @property int|null    $updated_by
 */
class CommercialOffer extends Model
{
    use HasFactory;

    protected $fillable = [
        'reference',
        'client_request_id',
        'client_id',
        'incoterm_id',
        'origin_port_id',
        'destination_port_id',
        'container_type_id',
        'price',
        'valid_until',
        'status',
        'rejection_reason',
        'comments',
        'odoo_id',
        'created_by',
        'updated_by',
    ];

    protected $attributes = ['status' => 'draft'];

    /**
     * Casts de precisión decimal para el precio ofertado.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
        ];
    }

    /**
     * Normaliza fechas provenientes de SQL Server antes de delegar al parser Carbon.
     *
     * SQL Server devuelve fechas como "Feb 22 2028 12:00:00:AM" donde Carbon no
     * puede parsear ":AM"/":PM". Sustituimos los dos puntos finales por un espacio
     * para que el formato sea válido.
     *
     * @param  mixed  $value
     * @return \Illuminate\Support\Carbon|\Carbon\Carbon
     */
    protected function asDateTime($value)
    {
        if (is_string($value) && preg_match('/:\s*(AM|PM)\s*$/i', $value)) {
            $value = preg_replace('/:\s*(AM|PM)\s*$/i', ' $1', $value);
        }

        return parent::asDateTime($value);
    }

    /**
     * Filtra ofertas en estado borrador (`draft`).
     *
     * @param  Builder  $query
     * @return Builder
     */
    public function scopeDraft(Builder $query): Builder
    {
        return $query->where('status', 'draft');
    }

    /**
     * Filtra ofertas aceptadas por el cliente.
     *
     * @param  Builder  $query
     * @return Builder
     */
    public function scopeAccepted(Builder $query): Builder
    {
        return $query->where('status', 'accepted');
    }

    /**
     * Filtra ofertas rechazadas por el cliente.
     *
     * @param  Builder  $query
     * @return Builder
     */
    public function scopeRejected(Builder $query): Builder
    {
        return $query->where('status', 'rejected');
    }

    /**
     * Solicitud de cotización que originó la oferta.
     *
     * @return BelongsTo
     */
    public function clientRequest(): BelongsTo
    {
        return $this->belongsTo(ClientRequest::class);
    }

    /**
     * Cliente destinatario de la oferta.
     *
     * @return BelongsTo
     */
    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    /**
     * Incoterm concreto aplicado a esta oferta.
     *
     * @return BelongsTo
     */
    public function incoterm(): BelongsTo
    {
        return $this->belongsTo(Incoterm::class);
    }

    /**
     * Puerto de origen del envío cotizado.
     *
     * @return BelongsTo
     */
    public function originPort(): BelongsTo
    {
        return $this->belongsTo(Port::class, 'origin_port_id');
    }

    /**
     * Puerto de destino del envío cotizado.
     *
     * @return BelongsTo
     */
    public function destinationPort(): BelongsTo
    {
        return $this->belongsTo(Port::class, 'destination_port_id');
    }

    /**
     * Tipo de contenedor cotizado.
     *
     * @return BelongsTo
     */
    public function containerType(): BelongsTo
    {
        return $this->belongsTo(ContainerType::class);
    }

    /**
     * Usuario que creó la oferta.
     *
     * @return BelongsTo
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Usuario que actualizó la oferta por última vez.
     *
     * @return BelongsTo
     */
    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /**
     * Operación logística creada cuando la oferta es aceptada.
     *
     * @return HasOne
     */
    public function logisticsOperation(): HasOne
    {
        return $this->hasOne(LogisticsOperation::class);
    }
}
