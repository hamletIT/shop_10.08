<?php

namespace App\Models;

use Session;
use Illuminate\Database\Eloquent\Model;

class pivot_categories_products extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'product_id',
        'category_id',
        'created_at',
        'updated_at',
    ];
}