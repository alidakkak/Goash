<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LevelUser extends Model
{
    use HasFactory;
    protected $guarded = ['id'];


    public function user(){
        return $this->belongsTo(User::class);
    }
    public function level(){
        return $this->belongsTo(level::class);
    }
}
