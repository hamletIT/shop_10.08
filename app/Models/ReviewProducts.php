<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReviewProducts extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'user_id',
        'text',
        'created_at',
        'updated_at',
    ];
}
