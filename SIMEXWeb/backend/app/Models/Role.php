<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Rol de usuario dentro del sistema (p.ej. admin, operador, cliente).
 *
 * El nombre del rol se incrusta en el JWT como claim `role` y rige la
 * autorización frontend (`backend_is_admin` se deriva de `role.name !== 'cliente'`).
 *
 * @property int         $id
 * @property string      $name
 * @property string|null $description
 */
class Role extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = ['name', 'description'];

    /**
     * Usuarios con este rol.
     *
     * @return HasMany
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }
}
