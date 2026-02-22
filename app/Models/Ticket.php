<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    protected $fillable = ['fio', 'phone', 'amount', 'payment_id', 'is_paid'];

    protected $casts = [
        'is_paid' => 'boolean',
    ];
}
