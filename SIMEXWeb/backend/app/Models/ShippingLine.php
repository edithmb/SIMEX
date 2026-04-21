<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Naviera (shipping line) asociada a la ciudad donde opera.
 *
 * @property int    $id
 * @property string $name
 * @property int    $city_id FK a `cities`.
 */
class ShippingLine extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = ['name', 'city_id'];

    /**
     * Ciudad base de la naviera.
     *
     * @return BelongsTo
     */
    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class);
    }
}
