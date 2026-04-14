<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Incoterm;

class LogisticsOperation extends Model
{
    use HasFactory;

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

    protected $appends = ['incoterm_steps'];

    public function getIncotermStepsAttribute(): array
    {
        $offer = $this->commercialOffer;
        if (!$offer || !$offer->incoterm || !$offer->clientRequest) {
            return [];
        }

        $responsability = $offer->clientRequest->responsability;
        if (!$responsability) {
            return [];
        }

        return Incoterm::where('incoterm_type_id', $offer->incoterm->incoterm_type_id)
            ->where('responsability', $responsability)
            ->orderBy('order_num')
            ->with('trackingStep:id,name')
            ->get()
            ->map(fn ($i) => [
                'id'             => $i->id,
                'order'          => $i->order_num,
                'name'           => $i->trackingStep?->name,
                'responsability' => $i->responsability,
            ])
            ->all();
    }

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
        return $query->where('status', 'completed')->whereNotNull('completed_at');
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
