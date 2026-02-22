<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    protected $fillable = [
        'fio',
        'phone',
        'hours',
        'skate_id',
        'skate_model',
        'skate_size',
        'total_amount',
        'payment_id',
        'payment_url',
        'status',
        'is_paid',
        'has_skates',
        'paid_at'
    ];

    protected $casts = [
        'is_paid' => 'boolean',
        'has_skates' => 'boolean',
        'paid_at' => 'datetime',
    ];

    public function skate()
    {
        return $this->belongsTo(Skate::class);
    }

    public function getSkateInfoAttribute()
    {
        if (!$this->has_skates) {
            return 'Свои коньки';
        }

        if ($this->skate) {
            return $this->skate->model . ' (размер ' . $this->skate->size . ')';
        }

        if ($this->skate_model) {
            return $this->skate_model . ' (размер ' . $this->skate_size . ') [архив]';
        }

        return 'Коньки не указаны';
    }
}
