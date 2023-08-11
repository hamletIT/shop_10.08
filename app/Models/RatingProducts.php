<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RatingProducts extends Model
{
    use HasFactory;

    protected $fillable = [
        'store_id',
        'product_id',
        'user_id',
        'rating',
        'created_at',
        'updated_at',
    ];
}
