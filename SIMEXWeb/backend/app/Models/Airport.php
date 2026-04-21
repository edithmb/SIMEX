<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Aeropuerto catalogado en el sistema, vinculado a la ciudad en la que se ubica.
 *
 * Se usa como dato maestro de origen/destino aéreo en ofertas y operaciones logísticas.
 *
 * @property int    $id
 * @property string $code    Código IATA del aeropuerto.
 * @property string $name    Nombre del aeropuerto.
 * @property int    $city_id FK a `cities`.
 */
class Airport extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = ['code', 'name', 'city_id'];

    /**
     * Ciudad a la que pertenece el aeropuerto.
     *
     * @return BelongsTo
     */
    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class);
    }
}
