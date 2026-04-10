<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class CommercialOffer extends Model
{
    use HasFactory;

    protected $fillable = [
        'reference',
        'client_request_id',
        'client_id',
        'incoterm_id',
        'origin_port_id',
        'destination_port_id',
        'container_type_id',
        'price',
        'valid_until',
        'status',
        'rejection_reason',
        'comments',
        'odoo_id',
        'created_by',
        'updated_by',
    ];

    protected $attributes = ['status' => 'draft'];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
        ];
    }

    /**
     * SQL Server devuelve fechas como "Feb 22 2028 12:00:00:AM"
     * donde Carbon no puede parsear ":AM"/":PM". Normalizamos aquí.
     */
    protected function asDateTime($value)
    {
        if (is_string($value) && preg_match('/:\s*(AM|PM)\s*$/i', $value)) {
            $value = preg_replace('/:\s*(AM|PM)\s*$/i', ' $1', $value);
        }

        return parent::asDateTime($value);
    }

    public function scopeDraft(Builder $query): Builder
    {
        return $query->where('status', 'draft');
    }

    public function scopeAccepted(Builder $query): Builder
    {
        return $query->where('status', 'accepted');
    }

    public function scopeRejected(Builder $query): Builder
    {
        return $query->where('status', 'rejected');
    }

    public function clientRequest(): BelongsTo
    {
        return $this->belongsTo(ClientRequest::class);
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function incoterm(): BelongsTo
    {
        return $this->belongsTo(Incoterm::class);
    }

    public function originPort(): BelongsTo
    {
        return $this->belongsTo(Port::class, 'origin_port_id');
    }

    public function destinationPort(): BelongsTo
    {
        return $this->belongsTo(Port::class, 'destination_port_id');
    }

    public function containerType(): BelongsTo
    {
        return $this->belongsTo(ContainerType::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function logisticsOperation(): HasOne
    {
        return $this->hasOne(LogisticsOperation::class);
    }
}
