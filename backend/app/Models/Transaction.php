<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $table = 'transaction';

    protected $fillable = [
        'user_id',
        'transaction_code',
        'total_amount',
        'transaction_status',
        'payment_method',
        'midtrans_order_id',
        'paid_at',
    ];
}
