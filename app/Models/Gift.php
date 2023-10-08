<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gift extends Model
{
    use HasFactory;
    protected $guarded = ['id'];


    public function giftUser()
    {
        return $this->hasMany(GiftUser::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function setImageAttribute ($image){
        $newImageName = uniqid() . '_' . 'gift_image' . '.' . $image->extension();
        $image->move(public_path('gift_image') , $newImageName);
        return $this->attributes['image'] =  '/'.'gift_image'.'/' . $newImageName;
    }

}
