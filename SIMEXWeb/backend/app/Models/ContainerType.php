<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ContainerType extends Model
{
    public $timestamps = false;

    protected $fillable = ['type_name'];

    public function commercialOffers(): HasMany
    {
        return $this->hasMany(CommercialOffer::class);
    }
}
