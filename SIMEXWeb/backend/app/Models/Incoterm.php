<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Incoterm extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = ['incoterm_type_id', 'tracking_step_id', 'order_num', 'responsability'];

    public function incotermType(): BelongsTo
    {
        return $this->belongsTo(IncotermType::class);
    }

    public function trackingStep(): BelongsTo
    {
        return $this->belongsTo(TrackingStep::class);
    }

    public function commercialOffers(): HasMany
    {
        return $this->hasMany(CommercialOffer::class);
    }
}
