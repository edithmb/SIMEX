<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class IncotermType extends Model
{
    public $timestamps = false;

    protected $fillable = ['code', 'name'];

    public function incoterms(): HasMany
    {
        return $this->hasMany(Incoterm::class);
    }
}
