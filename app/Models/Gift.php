<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gift extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public function giftUser() {
        return $this->hasMany(GiftUser::class);
    }


}