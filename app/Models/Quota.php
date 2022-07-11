<?php

namespace App\Models;

use App\Enums\Quota\Basis;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Quota extends Model
{
    use HasFactory;

    protected $fillable = [
        'basis',
        'regular_amount',
        'special_amount',
        'is_current_plan',
    ];

    protected $casts = [
        'basis' => Basis::class,
        'is_current_plan' => 'boolean',
    ];
}
