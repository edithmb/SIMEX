<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Sesión de login registrada al emitir un JWT.
 *
 * Su `id` se embebe en el claim `sid` del JWT para permitir invalidar una
 * sesión concreta en logout. `logged_out_at` se rellena al cerrar sesión;
 * un valor NULL indica que la sesión sigue activa hasta `token_expires_at`.
 *
 * @property int         $id
 * @property int         $user_id
 * @property string|null $ip_address
 * @property string|null $user_agent
 * @property string|null $device_type      Dispositivo inferido (desktop|tablet|mobile|unknown).
 * @property \Illuminate\Support\Carbon      $logged_in_at
 * @property \Illuminate\Support\Carbon|null $logged_out_at
 * @property \Illuminate\Support\Carbon      $token_expires_at
 */
class LoginSession extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'ip_address',
        'user_agent',
        'device_type',
        'logged_in_at',
        'logged_out_at',
        'token_expires_at',
    ];

    /**
     * Casts de fechas a Carbon.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'logged_in_at' => 'datetime',
            'logged_out_at' => 'datetime',
            'token_expires_at' => 'datetime',
        ];
    }

    /**
     * Usuario propietario de la sesión.
     *
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
