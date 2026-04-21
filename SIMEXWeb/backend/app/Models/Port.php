<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Puerto marítimo; dato maestro usado como origen/destino en ofertas comerciales.
 *
 * @property int    $id
 * @property string $name
 * @property int    $city_id FK a `cities`.
 */
class Port extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = ['name', 'city_id'];

    /**
     * Ciudad en la que se sitúa el puerto.
     *
     * @return BelongsTo
     */
    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class);
    }

    /**
     * Ofertas comerciales que lo usan como puerto de origen.
     *
     * @return HasMany
     */
    public function originCommercialOffers(): HasMany
    {
        return $this->hasMany(CommercialOffer::class, 'origin_port_id');
    }

    /**
     * Ofertas comerciales que lo usan como puerto de destino.
     *
     * @return HasMany
     */
    public function destinationCommercialOffers(): HasMany
    {
        return $this->hasMany(CommercialOffer::class, 'destination_port_id');
    }
}
