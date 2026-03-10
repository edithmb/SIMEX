<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Location extends Model
{
    public $timestamps = false;

    protected $fillable = ['name', 'client_id', 'latitude', 'longitude', 'city_id'];

    protected function casts(): array
    {
        return [
            'latitude' => 'decimal:8',
            'longitude' => 'decimal:8',
        ];
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class);
    }

    public function originRequests(): HasMany
    {
        return $this->hasMany(ClientRequest::class, 'origin_id');
    }

    public function destinationRequests(): HasMany
    {
        return $this->hasMany(ClientRequest::class, 'destination_id');
    }
}
