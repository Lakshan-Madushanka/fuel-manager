<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Consumption extends Model
{
    use HasFactory;

    protected $fillable = [
      'amount',
    ];

    public const CREATED_AT = 'consumed_at';
    public const UPDATED_AT = null;
}
