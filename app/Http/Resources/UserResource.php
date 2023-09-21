<?php

namespace App\Http\Resources;

use App\Models\Gift;
use App\Models\Level;
use App\Models\LevelUser;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'phone' => $this->phone,
            'email' => $this->email,
            'role' => $this->user_type,
            'image' => $this->image,
            'birthday' => $this->birthday,
            'points' => $this->points,
            'relationship' => [
                'gifts' => GiftResource::collection(Gift::whereHas('giftUser' , fn($query) => 
                    $query->where('user_id' , $this->id)
                )->get()),
                'level' => LevelResource::make(Level::whereHas('levelUser' , fn($query) => 
                    $query->where('user_id' , $this->id)->orderBy('id' , 'desc')->first()
                )->first())
            ]
        ];
    }
}
