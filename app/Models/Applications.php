<?php

namespace App\Models;
use Session;

use Illuminate\Database\Eloquent\Model;

class Applications extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'phone',
        'password',
        'text'
    ];

    /**
     * Get the phone record associated with the user.
     */
    public function product()
    {
        return $this->belongsTo('App\Models\Products');
    }
}