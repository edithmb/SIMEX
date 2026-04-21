<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Ubicación física del cliente (almacén, fábrica, oficina) con coordenadas.
 *
 * Sirve como origen/destino en las solicitudes de cotización.
 *
 * @property int         $id
 * @property string      $name
 * @property int         $client_id FK a `clients`.
 * @property string|null $latitude  Decimal 8 dígitos (precisión ~1mm).
 * @property string|null $longitude Decimal 8 dígitos.
 * @property int         $city_id   FK a `cities`.
 */
class Location extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = ['name', 'client_id', 'latitude', 'longitude', 'city_id'];

    /**
     * Casts de precisión decimal para coordenadas geográficas.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'latitude' => 'decimal:8',
            'longitude' => 'decimal:8',
        ];
    }

    /**
     * Cliente propietario de la ubicación.
     *
     * @return BelongsTo
     */
    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    /**
     * Ciudad donde se ubica.
     *
     * @return BelongsTo
     */
    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class);
    }

    /**
     * Solicitudes de cotización que usan esta ubicación como origen.
     *
     * @return HasMany
     */
    public function originRequests(): HasMany
    {
        return $this->hasMany(ClientRequest::class, 'origin_id');
    }

    /**
     * Solicitudes de cotización que usan esta ubicación como destino.
     *
     * @return HasMany
     */
    public function destinationRequests(): HasMany
    {
        return $this->hasMany(ClientRequest::class, 'destination_id');
    }
}
