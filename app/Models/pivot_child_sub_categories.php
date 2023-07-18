<?php

namespace App\Models;
use Session;

use Illuminate\Database\Eloquent\Model;

class pivot_child_sub_categories extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'sub_category_id',
        'child_sub_category_id',
        'product_id',
        'created_at',
        'updated_at',
    ];

}