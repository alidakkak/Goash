<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Level extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public function setImageAttribute ($image){
        $newImageName = uniqid() . '_' . 'level_image' . '.' . $image->extension();
        $image->move(public_path('level_images') , $newImageName);
        return $this->attributes['image'] =  '/'.'level_images'.'/' . $newImageName;
    }

    public function levelUser(){
        return $this->hasMany(LevelUser::class);
    }

    public function featureLevel() {
        return $this->hasMany(FeatureLevel::class);
    }
}
