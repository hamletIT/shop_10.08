<?php

namespace App\Models;
use Session;

use Illuminate\Database\Eloquent\Model;
use App\Models\Carts;
use App\Models\Products;
use App\Models\OptionPhotos;

class Options extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'product_id',
        'name',
        'name_info',
        'status',
        'price',
        'photoFileName',
        'photoFilePath',
    ];

    /**
     * Get the phone record associated with the user.
     */
    public function cart()
    {
        return $this->hasOne(Carts::class);
    }

    public function product()
    {
        return $this->belongsTo(Products::class);
    }

    public function optionImages()
    {
        return $this->hasMany(OptionPhotos::class,'option_id');
    }
}
