<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Laravel\Passport\HasApiTokens;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;

class Rating extends Model
{
    use Notifiable, HasApiTokens, HasFactory;


    protected $fillable = [
        'product_id',
        'count',
        'rate',
    ];
}
