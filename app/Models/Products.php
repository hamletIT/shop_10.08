<?php

namespace App\Models;

use App\Models\Carts;
use App\Models\Photos;
use App\Models\Prices;
use App\Models\Category;
use App\Models\Rating;
use Laravel\Passport\HasApiTokens;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;

class Products extends Model
{
    use Notifiable, HasApiTokens, HasFactory;
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'description',
        'image',
        'count',
        'created_at',
        'updated_at',
    ];

    public function cart()
    {
        return $this->hasOne(Carts::class);
    }

    public function productImages()
    {
        return $this->hasMany(Photos::class,'product_id');
    }

    public function productPrice()
    {
        return $this->hasMany(Prices::class,'product_id');
    }

    public function ratingProducts()
    {
        return $this->hasMany(RatingProducts::class,'product_id');
    }

    public function reviewProducts()
    {
        return $this->hasMany(ReviewProducts::class,'product_id');
    }

    public function productRating()
    {
        return $this->hasMany(Rating::class,'product_id');
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }
}