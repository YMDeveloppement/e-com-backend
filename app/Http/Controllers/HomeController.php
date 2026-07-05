<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProductResource;
use App\Http\Resources\CategoryResource;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Product;

class HomeController extends Controller
{
    public function index()
    {

        $home_categories = Category::where(['home_status' => 1])->with(['parent'])->get();
        $home_categories_products =  [];
        foreach ($home_categories as $category) {
            $home_categories_products[$category->slug] = ProductResource::collection(Product::where('category_id', $category->id)->get());
        }

        return response()->json([
            'categories' => CategoryResource::collection($home_categories),
            'home_categories_products' => $home_categories_products
        ]);
    }
}
