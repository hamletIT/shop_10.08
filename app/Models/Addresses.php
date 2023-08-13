<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Addresses extends Model
{
    protected $fillable = [
        'user_id',
        'house_number',
        'street_name',
        'city',
        'status',
        'saved_address_status',
        'phone',
    ];
    
}
