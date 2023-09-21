<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Feature extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    //feature_levels
    public function featureLevel() {
        return $this->hasMany(FeatureLevel::class);
    }
}
