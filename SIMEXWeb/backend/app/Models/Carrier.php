<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Transportista (carrier) terrestre asociado a la ciudad donde opera.
 *
 * Dato maestro usado al planificar trayectos terrestres dentro de una operación logística.
 *
 * @property int    $id
 * @property string $name    Nombre comercial del transportista.
 * @property int    $city_id FK a `cities`.
 */
class Carrier extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = ['name', 'city_id'];

    /**
     * Ciudad base del transportista.
     *
     * @return BelongsTo
     */
    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class);
    }
}
