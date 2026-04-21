<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Tipo de contenedor marítimo (20', 40', 40HC…) usado en ofertas comerciales.
 *
 * @property int    $id
 * @property string $type_name Nombre del tipo (p.ej. "20DC", "40HC").
 */
class ContainerType extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = ['type_name'];

    /**
     * Ofertas comerciales que usan este tipo de contenedor.
     *
     * @return HasMany
     */
    public function commercialOffers(): HasMany
    {
        return $this->hasMany(CommercialOffer::class);
    }
}
