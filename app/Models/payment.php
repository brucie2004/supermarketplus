<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'stripe_payment_intent_id',
        'status',
        'amount',
        'currency',
        'payment_method_details'
    ];

    protected $casts = [
        'payment_method_details' => 'array',
        'amount' => 'decimal:2'
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}