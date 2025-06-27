<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DonutResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'seal_of_approval' => $this->seal_of_approval,
            'price' => $this->price,
            'image_url' => $this->image_url ?? null,
            'created_at' => $this->created_at,
        ];
    }
}
