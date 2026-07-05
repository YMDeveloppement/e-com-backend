<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Category extends Model
{
    use HasFactory  , HasUuids;

    protected $fillable = ['name', 'slug', 'image_url'  , 'description' , 'is_active'];

    function products() :HasMany{
        return $this->hasMany(Product::class);
    }
    function parent() :HasOne{
        return $this->hasOne(Category::class, 'id', 'parent_id');
    }
    function childs() :HasMany{
        return $this->hasMany(Category::class, 'parent_id', 'id');
    }
}
    