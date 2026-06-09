<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'order_id',
        'amount',
        'status',
        'email',
        'address',
        'latitude',
        'longitude',
        'premium_protection',
        'snap_token',
        'album_title',
        'order_status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
