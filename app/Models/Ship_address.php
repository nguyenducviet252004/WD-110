<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ship_address extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'ship_address',
        'phone_number',
        'recipient_name',
        'is_default',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
