<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class TempOrder extends Model
{
    use HasFactory;

    protected $table = 'temp_orders';

    protected $fillable = [
        'order_id',
        'user_id',
        'product_id',
        'quantity',
        'price',
    ];
}
