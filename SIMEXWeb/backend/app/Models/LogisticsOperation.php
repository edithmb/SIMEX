<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LogisticsOperation extends Model
{
    protected $fillable = [
        'reference',
        'commercial_offer_id',
        'client_id',
        'status',
        'etd',
        'eta',
        'atd',
        'ata',
        'odoo_id',
        'completed_at',
    ];

    protected $attributes = ['status' => 'preparation'];

    protected function casts(): array
    {
        return [
            'etd' => 'date',
            'eta' => 'date',
            'atd' => 'date',
            'ata' => 'date',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
            'completed_at' => 'datetime',
        ];
    }

    public function scopeInPreparation(Builder $query): Builder
    {
        return $query->where('status', 'preparation');
    }

    public function scopeCompleted(Builder $query): Builder
    {
        return $query->whereNotNull('completed_at');
    }

    public function scopeByStatus(Builder $query, string $status): Builder
    {
        return $query->where('status', $status);
    }

    public function commercialOffer(): BelongsTo
    {
        return $this->belongsTo(CommercialOffer::class);
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }
}
