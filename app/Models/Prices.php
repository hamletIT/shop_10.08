<?php

namespace App\Models;
use Session;
use Illuminate\Database\Eloquent\Model;

class Prices extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'product_id',
        'price',
        'created_at',
        'updated_at',
    ];
}