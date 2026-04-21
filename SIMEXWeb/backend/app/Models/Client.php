<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Empresa cliente de SIMEX. Agrupa sus usuarios, ubicaciones, solicitudes,
 * ofertas y operaciones logísticas.
 *
 * Usa soft-deletes (baja lógica) y rastrea autoría con `created_by`, `updated_by`
 * y `deleted_by`. `$timestamps = false` porque las columnas de auditoría se gestionan
 * explícitamente desde los controllers (y algunas tablas en SQL Server no tienen
 * `updated_at` estándar).
 *
 * @property int         $id
 * @property string      $company_name Razón social.
 * @property string|null $vat_number   NIF/CIF/VAT.
 * @property string|null $address
 * @property string|null $country
 * @property string|null $postal_code
 * @property string|null $contact_name Persona de contacto.
 * @property string|null $email
 * @property string|null $phone
 * @property int|null    $odoo_int     ID sincronizado con Odoo.
 * @property int|null    $created_by   FK al usuario que creó el cliente.
 * @property int|null    $updated_by   FK al usuario que lo actualizó por última vez.
 * @property int|null    $deleted_by   FK al usuario que lo dio de baja.
 */
class Client extends Model
{
    use HasFactory, SoftDeletes;

    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'company_name',
        'vat_number',
        'address',
        'country',
        'postal_code',
        'contact_name',
        'email',
        'phone',
        'odoo_int',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    /**
     * Casts explícitos de las marcas de tiempo a instancias Carbon.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
            'deleted_at' => 'datetime',
        ];
    }

    /**
     * Usuario que creó al cliente.
     *
     * @return BelongsTo
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Usuario que actualizó al cliente por última vez.
     *
     * @return BelongsTo
     */
    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /**
     * Usuario que aplicó la baja lógica del cliente.
     *
     * @return BelongsTo
     */
    public function deleter(): BelongsTo
    {
        return $this->belongsTo(User::class, 'deleted_by');
    }

    /**
     * Usuarios vinculados al cliente.
     *
     * @return HasMany
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    /**
     * Ubicaciones (direcciones de carga/descarga) del cliente.
     *
     * @return HasMany
     */
    public function locations(): HasMany
    {
        return $this->hasMany(Location::class);
    }

    /**
     * Solicitudes de cotización realizadas por el cliente.
     *
     * @return HasMany
     */
    public function clientRequests(): HasMany
    {
        return $this->hasMany(ClientRequest::class);
    }

    /**
     * Ofertas comerciales emitidas para el cliente.
     *
     * @return HasMany
     */
    public function commercialOffers(): HasMany
    {
        return $this->hasMany(CommercialOffer::class);
    }

    /**
     * Operaciones logísticas del cliente (una vez la oferta es aceptada).
     *
     * @return HasMany
     */
    public function logisticsOperations(): HasMany
    {
        return $this->hasMany(LogisticsOperation::class);
    }
}
