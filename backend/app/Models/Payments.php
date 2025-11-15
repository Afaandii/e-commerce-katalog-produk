<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payments extends Model
{
    use HasFactory;

    protected $table = 'payments';

    protected $fillable = [
        'transaction_id',
        'midtrans_transaction_id',
        'payment_type',
        'transaction_status',
        'gross_amount',
        'raw_response',
    ];
}
