<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ClientRequest extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'client_id',
        'volume_m3',
        'gross_weight_kg',
        'comments',
        'origin_id',
        'destination_id',
        'created_by',
    ];

    protected function casts(): array
    {
        return [
            'volume_m3' => 'decimal:2',
            'gross_weight_kg' => 'decimal:2',
            'created_at' => 'datetime',
        ];
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function origin(): BelongsTo
    {
        return $this->belongsTo(Location::class, 'origin_id');
    }

    public function destination(): BelongsTo
    {
        return $this->belongsTo(Location::class, 'destination_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function commercialOffers(): HasMany
    {
        return $this->hasMany(CommercialOffer::class);
    }
}
