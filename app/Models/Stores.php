<?php

namespace App\Models;
use Session;
use Illuminate\Database\Eloquent\Model;
use App\Models\Photos;

class Stores extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'name',
        'info',
        'status',
        'lng',
        'lat',
        'created_at',
        'updated_at',
    ];
    
    public function photos()
    {
        return $this->belongsTo(Photos::class);
    }
}