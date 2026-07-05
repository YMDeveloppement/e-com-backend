<?php

namespace App\Http\Resources\admin;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

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
            'id' => $this->id,
            'category' => $this->category?->name,
            'brand' => $this->brand?->name,
            'name' => $this->name,
            'slug' => $this->slug,
            'description' => $this->description,
            'short_desc' => substr($this->description, 0, 40),
            'image_url' => $this->image_url,
            'base_price' => (float) $this->base_price,
            'compare_price' => $this->compare_price
                ? (float) $this->compare_price
                : null,
            'cost_price' => $this->cost_price
                ? (float) $this->cost_price
                : null,
            'tax_rate' => (float) $this->tax_rate,
            'weight_grams' => $this->weight_grams,
            'is_active' => (bool) $this->is_active,
            'is_featured' => (bool) $this->is_featured,
            'meta_title' => $this->meta_title,
            'meta_description' => $this->meta_description,
            'rating_avg' => (float) $this->rating_avg,
            'rating_count' => (int) $this->rating_count,
            'sold_count' => (int) $this->sold_count,
            'created_at' => $this->created_at?->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at?->format('Y-m-d H:i:s'),
            'is_organic' => (bool) $this->is_organic,
        ];
    }
}
