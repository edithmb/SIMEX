<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Port extends Model
{
    public $timestamps = false;

    protected $fillable = ['name', 'city_id'];

    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class);
    }

    public function originCommercialOffers(): HasMany
    {
        return $this->hasMany(CommercialOffer::class, 'origin_port_id');
    }

    public function destinationCommercialOffers(): HasMany
    {
        return $this->hasMany(CommercialOffer::class, 'destination_port_id');
    }
}
