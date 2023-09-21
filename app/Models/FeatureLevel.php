<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FeatureLevel extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public function level()
    {
        return $this->belongsTo(Level::class);
    }
    
    public function feature()
    {
        return $this->belongsTo(Feature::class);
    }
    
}
