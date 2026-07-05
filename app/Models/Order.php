<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Order extends Model
{
    use HasFactory;
    protected $fillable = [
        "user_id",
        'subtotal',
        'tax',
        'shipping_cost',
        'discount',
        'total'
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($order) {
            // Generates a clean, date-sortable unique ID
            // Example Output: ORD-01ARZ3NDEKTSV4RRFFQ69G5FAV
            $order->order_number = 'ORD-' . strtoupper(Str::ulid());
        });
    }
}
