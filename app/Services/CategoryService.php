<?php

namespace App\Services;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Support\Facades\DB;

class CategoryService
{
    public function RelatedCategories(Category $subCategory)
    {

        $categories = [];
        if ($subCategory->parent) {
            $categories = Category::whereHas('parent', function ($q) use ($subCategory) {
                $q->where('id', $subCategory->parent->id);
            })->get();
        } elseif ($subCategory->childs) {
            $categories = $subCategory->childs;
        } else {
            $categories = Category::has('parent')->take(10)->get();
        }

        return $categories;
    }

    public function maxPrice(Category $category)
    {
        $maxPrice = Product::where('category_id', $category->id)->max('base_price');
        return $maxPrice;
    }

    public function minPrice(Category $category)
    {
        $minPrice = Product::where('category_id', $category->id)->min('base_price');
        return $minPrice;
    }

    public function brands($products)
    {
        $brands = $products->pluck('brand.name')->unique();
        return $brands;

    }

    public function productsByFilter($products){

    }


}
