<?php

namespace App\Models;

use Session;
use Illuminate\Database\Eloquent\Model;
use App\Models\Products;
use App\Models\Stores;
use App\Models\Addresses;
use App\Models\User;


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
        'address_id',
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

    public function address()
    {
        return $this->belongsTo(Products::class);
    }

    public function address_order()
    {
        return $this->belongsTo(Addresses::class,'address_id');
    }

    public function users()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}