<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GiftUser extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public function gift() {
        return $this->belongsTo(Gift::class);
    }

    public function user() {
        return $this->belongsTo(User::class);
    }
}
