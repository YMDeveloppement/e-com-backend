<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id"=> $this->id,
            "category_id" => $this->category_id,
            "name"=> $this->name,
            "description"=> $this->description,
            "short_desc"=> $this->short_desc,
            "image"=> Storage::url($this->image_url),
            "price"=> $this->base_price,
            "compare_price"=> $this->compare_price,
            "base_price"=> $this->base_price,
            "cost_price"=> $this->cost_price,
            "tax_rate__"=> intval(((($this->cost_price / $this->compare_price) * 100))),
            "tax_rate"=> intval($this->tax_rate),
            // "classement"=> $this->price,
            "rating_count"=> $this->rating_count,
            "sold_count"=> $this->sold_count,
            "is_organic"=> $this->is_organic,
        ];
    }
}
