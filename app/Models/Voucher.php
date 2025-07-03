<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Voucher extends Model
{
    use HasFactory;

     protected $fillable = [
        'code',
        'title',
        'description',
        'start_date_time',
        'end_date_time',
        'discount',
        'is_active',
        'min_order_amount',
        'used_count',
        'max_usage',
    ];


     protected $casts = [
        'start_date_time' => 'datetime',
        'end_date_time' => 'datetime',
        'is_active' => 'boolean',
    ];

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_vouchers')
            ->withPivot('usage_count', 'is_used', 'assigned_at')
            ->withTimestamps();
    }
}