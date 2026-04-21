<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Solicitud de cotización creada por un cliente.
 *
 * Es la entrada del flujo comercial: el cliente describe la carga (volumen,
 * peso) y las ubicaciones de origen/destino. Desactivamos `UPDATED_AT` porque
 * las solicitudes sólo llevan `created_at` en la BD.
 *
 * @property int         $id
 * @property int         $client_id
 * @property string      $volume_m3       Volumen de la carga (m³, decimal 2).
 * @property string      $gross_weight_kg Peso bruto (kg, decimal 2).
 * @property string|null $comments
 * @property int         $origin_id       FK a `locations`.
 * @property int         $destination_id  FK a `locations`.
 * @property int         $created_by      FK al usuario que la creó.
 * @property string      $estado          Estado del workflow (pendiente/cotizada/…).
 * @property string      $responsability  Responsabilidad Incoterm asociada (p.ej. 'vendedor', 'comprador').
 */
class ClientRequest extends Model
{
    use HasFactory;

    const UPDATED_AT = null;

    protected $fillable = [
        'client_id',
        'volume_m3',
        'gross_weight_kg',
        'comments',
        'origin_id',
        'destination_id',
        'created_by',
        'estado',
        'responsability',
    ];

    /**
     * Casts de campos numéricos con precisión decimal.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'volume_m3' => 'decimal:2',
            'gross_weight_kg' => 'decimal:2',
        ];
    }

    /**
     * Normaliza fechas provenientes de SQL Server antes de delegar al parser Carbon.
     *
     * SQL Server devuelve fechas como "Feb 22 2028 12:00:00:AM" donde Carbon no
     * puede parsear ":AM"/":PM". Sustituimos los dos puntos finales por un espacio
     * para que el formato sea válido.
     *
     * @param  mixed  $value  Valor a convertir (normalmente string o Carbon).
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
     * Cliente al que pertenece la solicitud.
     *
     * @return BelongsTo
     */
    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    /**
     * Ubicación de origen de la carga.
     *
     * @return BelongsTo
     */
    public function origin(): BelongsTo
    {
        return $this->belongsTo(Location::class, 'origin_id');
    }

    /**
     * Ubicación de destino de la carga.
     *
     * @return BelongsTo
     */
    public function destination(): BelongsTo
    {
        return $this->belongsTo(Location::class, 'destination_id');
    }

    /**
     * Usuario que creó la solicitud.
     *
     * @return BelongsTo
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Ofertas comerciales emitidas en respuesta a esta solicitud.
     *
     * @return HasMany
     */
    public function commercialOffers(): HasMany
    {
        return $this->hasMany(CommercialOffer::class);
    }
}
