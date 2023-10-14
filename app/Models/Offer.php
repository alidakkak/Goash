<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Offer extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public function setImageAttribute ($image){
        $newImageName = uniqid() . '_' . 'offer_image' . '.' . $image->extension();
        $image->move(public_path('offer_image') , $newImageName);
        return $this->attributes['image'] =  '/'.'offer_image'.'/' . $newImageName;
    }
}
