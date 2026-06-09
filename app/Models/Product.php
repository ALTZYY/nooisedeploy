<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Relations\HasMany;

use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Product extends Model
{
    use HasUuids;

    protected $keyType = 'string';
    public $incrementing = false;

    public function carts(): HasMany
    {
        return $this->hasMany(Cart::class);
    }
}

