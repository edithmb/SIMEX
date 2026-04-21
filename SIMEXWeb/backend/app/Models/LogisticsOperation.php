<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Incoterm;

/**
 * Operación logística: seguimiento del envío tras aceptarse la oferta comercial.
 *
 * Mantiene fechas estimadas (`etd`/`eta`) y reales (`atd`/`ata`) del trayecto,
 * el estado (`preparation` → `completed`) y una lista dinámica de pasos de
 * tracking (`incoterm_steps`) derivada del Incoterm y la responsabilidad de la
 * solicitud asociada.
 *
 * @property int         $id
 * @property string      $reference           Referencia legible de la operación.
 * @property int         $commercial_offer_id FK a la oferta aceptada.
 * @property int         $client_id
 * @property string      $status              Estado: preparation|completed|…
 * @property \Illuminate\Support\Carbon|null $etd           Fecha estimada de salida.
 * @property \Illuminate\Support\Carbon|null $eta           Fecha estimada de llegada.
 * @property \Illuminate\Support\Carbon|null $atd           Fecha real de salida.
 * @property \Illuminate\Support\Carbon|null $ata           Fecha real de llegada.
 * @property int|null    $odoo_id
 * @property \Illuminate\Support\Carbon|null $completed_at Cuándo se cerró la operación.
 * @property-read array  $incoterm_steps      Pasos de tracking derivados (append).
 */
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

    /**
     * Accessor computado: lista ordenada de pasos Incoterm aplicables a la
     * operación, filtrados por el tipo Incoterm de la oferta y la
     * responsabilidad declarada en la solicitud.
     *
     * Devuelve un array vacío cuando falta la oferta, su Incoterm, su
     * solicitud o la responsabilidad — ningún fallback “mágico”.
     *
     * @return array<int, array{id:int, order:int, name:?string, responsability:string}>
     */
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

    /**
     * Casts de fechas de trayecto y auditoría.
     *
     * @return array<string, string>
     */
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

    /**
     * Filtra operaciones en preparación (aún no iniciadas).
     *
     * @param  Builder  $query
     * @return Builder
     */
    public function scopeInPreparation(Builder $query): Builder
    {
        return $query->where('status', 'preparation');
    }

    /**
     * Filtra operaciones completadas; exige además `completed_at` para
     * evitar registros marcados sin timestamp de cierre.
     *
     * @param  Builder  $query
     * @return Builder
     */
    public function scopeCompleted(Builder $query): Builder
    {
        return $query->where('status', 'completed')->whereNotNull('completed_at');
    }

    /**
     * Filtra por un estado arbitrario.
     *
     * @param  Builder  $query
     * @param  string   $status
     * @return Builder
     */
    public function scopeByStatus(Builder $query, string $status): Builder
    {
        return $query->where('status', $status);
    }

    /**
     * Oferta comercial aceptada que originó la operación.
     *
     * @return BelongsTo
     */
    public function commercialOffer(): BelongsTo
    {
        return $this->belongsTo(CommercialOffer::class);
    }

    /**
     * Cliente propietario de la operación.
     *
     * @return BelongsTo
     */
    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    /**
     * Documentos asociados a la operación (B/L, facturas, etc.).
     *
     * @return HasMany
     */
    public function logisticsOperationDocuments(): HasMany
    {
        return $this->hasMany(LogisticsOperationDocument::class);
    }
}
