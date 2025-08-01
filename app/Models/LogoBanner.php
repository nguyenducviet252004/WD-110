<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class LogoBanner extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'title',
        'description',
        'image',
        'is_active',
    ];
}