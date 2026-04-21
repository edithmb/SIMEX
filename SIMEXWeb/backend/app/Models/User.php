<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;

/**
 * Usuario del sistema; implementa `JWTSubject` para integrarse con tymon/jwt-auth.
 *
 * Particularidades:
 * - Usa `password_hash` en lugar del `password` por defecto (sobrescribe
 *   `getAuthPassword`). El cast `hashed` hace que al asignar valor en claro
 *   se persista hasheado automáticamente.
 * - Soft-deletes activos (baja lógica con `deleted_by` para auditoría).
 * - Soporte de Fortify Two-Factor (secret y recovery codes ocultos).
 * - El JWT incluye claims personalizados `role` y `role_id`.
 *
 * @property int         $id
 * @property int         $role_id
 * @property int|null    $client_id    Nulo si no es usuario de un cliente (interno).
 * @property string      $first_name
 * @property string      $last_name
 * @property string      $email
 * @property string      $password_hash
 * @property string|null $phone_number
 * @property bool        $is_active
 * @property int|null    $odoo_id
 * @property int|null    $created_by
 * @property int|null    $updated_by
 * @property int|null    $deleted_by
 */
class User extends Authenticatable implements JWTSubject
{
    use HasFactory, Notifiable, TwoFactorAuthenticatable, SoftDeletes;

    protected $fillable = [
        'role_id',
        'client_id',
        'first_name',
        'last_name',
        'email',
        'password_hash',
        'phone_number',
        'is_active',
        'odoo_id',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $hidden = [
        'password_hash',
        'two_factor_secret',
        'two_factor_recovery_codes',
        'remember_token',
    ];

    /**
     * Casts a tipos PHP nativos. `password_hash => hashed` garantiza que
     * cualquier asignación en claro se hashee antes de persistir.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
            'deleted_at' => 'datetime',
            'password_hash' => 'hashed',
        ];
    }

    /**
     * Identificador que tymon/jwt-auth embebe como `sub` en el JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier(): mixed
    {
        return $this->getKey();
    }

    /**
     * Claims personalizados que se añaden al JWT emitido.
     *
     * Incluye `role` (nombre) y `role_id` para que el frontend pueda tomar
     * decisiones sin llamar a `/me` en escenarios simples.
     *
     * @return array<string, mixed>
     */
    public function getJWTCustomClaims(): array
    {
        return [
            'role' => $this->role->name,
            'role_id' => $this->role_id,
        ];
    }

    /**
     * Devuelve la contraseña hasheada que Laravel debe comparar al autenticar.
     *
     * @return string
     */
    public function getAuthPassword(): string
    {
        return $this->password_hash;
    }

    /**
     * Filtra usuarios activos (`is_active = true`).
     *
     * @param  Builder  $query
     * @return Builder
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    /**
     * Rol del usuario.
     *
     * @return BelongsTo
     */
    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    /**
     * Cliente al que pertenece (null para usuarios internos).
     *
     * @return BelongsTo
     */
    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    /**
     * Sesiones de login registradas para este usuario.
     *
     * @return HasMany
     */
    public function loginSessions(): HasMany
    {
        return $this->hasMany(LoginSession::class);
    }

    /**
     * Documentos subidos por el usuario.
     *
     * @return HasMany
     */
    public function documents(): HasMany
    {
        return $this->hasMany(Document::class, 'uploaded_by');
    }
}
