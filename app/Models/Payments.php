<?php

namespace App\Models;
use Session;

use Illuminate\Database\Eloquent\Model;

class Payments extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'totalFinalPrice',
        'payment_status',
        'status',
    ];

}