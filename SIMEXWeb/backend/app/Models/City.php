<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Ciudad perteneciente a un país; sirve de pivote para datos maestros geográficos
 * (aeropuertos, puertos, transportistas, navieras, ubicaciones de cliente).
 *
 * @property int    $id
 * @property string $name       Nombre de la ciudad.
 * @property int    $country_id FK a `countries`.
 */
class City extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = ['name', 'country_id'];

    /**
     * País al que pertenece la ciudad.
     *
     * @return BelongsTo
     */
    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }

    /**
     * Aeropuertos ubicados en la ciudad.
     *
     * @return HasMany
     */
    public function airports(): HasMany
    {
        return $this->hasMany(Airport::class);
    }

    /**
     * Puertos marítimos ubicados en la ciudad.
     *
     * @return HasMany
     */
    public function ports(): HasMany
    {
        return $this->hasMany(Port::class);
    }

    /**
     * Transportistas con base en la ciudad.
     *
     * @return HasMany
     */
    public function carriers(): HasMany
    {
        return $this->hasMany(Carrier::class);
    }

    /**
     * Navieras (shipping lines) con base en la ciudad.
     *
     * @return HasMany
     */
    public function shippingLines(): HasMany
    {
        return $this->hasMany(ShippingLine::class);
    }

    /**
     * Ubicaciones de clientes (almacenes, oficinas…) en la ciudad.
     *
     * @return HasMany
     */
    public function locations(): HasMany
    {
        return $this->hasMany(Location::class);
    }
}
