<?php

namespace App\Http\Controllers;

use App\Filters\ProductFilter;
use App\Models\Category;
use App\Models\Product;
use App\Services\CategoryService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use App\Http\Resources\ProductResource;
use Illuminate\Support\Facades\DB;

class CategoryController extends Controller
{
    function __construct(
        protected CategoryService $category_service,
        protected ProductFilter $product_filter
    ) {}
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
    
        $data = [];
        // return response()->json(DB::scalar('SELECT COUNT(*) FROM categories'));
        $categories = Category::all();

        foreach ($categories as $category) {
            $sub_categories = Category::where('parent_id', $category->id)->get();
            if (!$sub_categories)
                continue;


            if (!array_key_exists($category->slug, $data)) {
                $data[$category->slug] = [
                    'id' => $category->id,
                    'name' => $category->name,
                    'alias' => $category->slug
                ];
            }
            $data[$category->slug]['sub'] = $sub_categories;
        }
        return response()->json($data);
    }


    public function product_category(Category $category, Request $request)
    {
        $validated = $request->validate([
            'rate' => 'nullable|numeric|min:0|max:100',
            'brand' => 'nullable|exists:brands,id',
            'discount' => 'nullable|numeric|min:0|max:100',
            'min_price' => 'nullable|numeric|min:0',
            'max_price' => 'nullable|numeric|min:0|gte:min_price',
            'page' => 'nullable|numeric|min:1',
        ]);

        $data = [
            'title' => $category->name,
        ];

        $productsQuery = Product::query()
            ->where('category_id', $category->id)
            ->with(['category', 'brand']);

        // Discount filter
        
        $productsQuery = $this->product_filter->apply($productsQuery , $validated);


        $products = $productsQuery->paginate(10);

        // fallback to parent category
        if ($products->isEmpty() && $category->parent) {
            // dd([$productsQuery->toSql(), $category->parent?->id,$productsQuery->getBindings()]);
            return $this->product_category(
                $category->parent,
                $request
            );
        }


        $data['products'] = ProductResource::collection($products);

        $data['filter_data'] = [
            'subCategory' => $this->category_service->RelatedCategories($category),
            'minPrice' => !empty($validated['min_price']) ? $validated['min_price'] : $this->category_service->minPrice($category) ?? 0,
            'maxPrice' => !empty($validated['max_price']) ? $validated['max_price'] : $this->category_service->maxPrice($category) ?? 2000,
            'brands' => $this->category_service->brands($products),
            'discount' => !empty($validated['discount']) ? $validated['discount'] : 10,
        ];

        $data['pagination'] = [
            'current_page' => $products->currentPage(),
            'last_page' => $products->lastPage(),
            'per_page' => $products->perPage(),
            'total' => $products->total(),
            'count_pages' => floor($products->total() / $products->perPage())
        ];


        return response()->json($data);
    }

    public function affect(Category $category)
    {

        $response = Http::get('https://kolzsticks.github.io/Free-Ecommerce-Products-Api/main/products.json');
        $datatt = [];
        if ($response->successful()) {
            $data = $response->json();
            foreach ($data as $item) {
                $alias_parent =  Str::slug($item['category']);
                $datatt[] = [
                    'id' => (string) Str::uuid(),
                    // 'brand_id' => (Brand::inRandomOrder()->first())->id,
                    'category_id' => (Category::where("slug", $alias_parent)->first())?->id,

                    'name' => $item['name'],
                    'slug' => $alias_parent,
                    'description' => $item['description'],
                    'short_desc' => substr($item['description'], 0, 20),

                    'base_price' => $item['priceCents'],
                    'compare_price' => ($item['priceCents'] + ($item['priceCents'] * 0.2)),
                    'cost_price' => ($item['priceCents'] * 0.2),

                    'tax_rate' => random_int(5, 50),
                    'weight_grams' => random_int(1, 10),

                    'is_active' => true,

                    'rating_avg' => random_int(1, 5),
                    'rating_count' => random_int(1, 5),
                ];
            }
        }

        return response()->json($datatt);
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Category $category)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        //
    }
}
