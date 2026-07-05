<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CartResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        
        return [
            "id"=> $this->product_id,
            "qty"=> $this->qty,
            "price"=> $this->product->base_price,
            "image"=> $this->product->image_url,
            "name"=> $this->product->name,
            "user_id"=> $this->user_id,
        ];
    }
}
