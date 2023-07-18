<?php

namespace App\Models;

use Session;
use Illuminate\Database\Eloquent\Model;
use App\Models\Products;
use App\Models\Stores;

class Orders extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'store_id',
        'product_id',
        'method',
        'location',
        'totalQty',
        'order_number',
        'payment_status',
        'status',
        'order_note',
        'totalPrice',
        'customer_name',
        'customer_email',
        'customer_phone',
    ];

    public function product()
    {
        return $this->belongsTo(Products::class);
    }

    public function store()
    {
        return $this->belongsTo(Stores::class);
    }
}