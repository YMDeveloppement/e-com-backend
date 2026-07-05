<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Laravel\Scout\Searchable;

class Product extends Model
{
    use HasFactory, HasUuids, Searchable;

    protected $fillable = [
        'id',
        'brand_id',
        'name',
        'slug',
        'description',
        'short_desc',
        'image_url',
        'base_price',
        'compare_price',
        'cost_price',
        'tax_rate',
        'weight_grams',
        'is_active',
        'is_featured',
        'is_organic',
        'meta_title',
        'meta_description',
    ];
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }
    public function brand(): BelongsTo{
        return $this->belongsTo(Brand::class);
    }
    public function images(): MorphMany{
        return $this->morphMany(Image::class, 'imageable');
    }
    public function variants():HasMany
    {
        return $this->hasMany(ProductVariant::class);
    }
    public function toSearchableArray()
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'base_price' => $this->base_price,
        ];
    }

    public static function booted()
    {
        static::addGlobalScope('active', function ($query) {
            $query->where('is_active', true);
        });
    }
}
