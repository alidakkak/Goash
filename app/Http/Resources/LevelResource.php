<?php

namespace App\Http\Resources;

use App\Models\Feature;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LevelResource extends JsonResource
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
            'start_points' => $this->start_points,
            'end_points' => $this->end_points,
            'name' => $this->name,
            'image' => $this->image,
            'relationship' => [
                'features' => FeatureResource::collection(Feature::whereHas('featureLevel' , fn($query) => 
                    $query->where('level_id' , $this->id)
                )->get())
            ]
        ];
    }
}
