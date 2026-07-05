<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $response = Http::get('https://dummyjson.com/products?limit=100&skip=0');

        if (!$response->successful()) {
            $this->command->error('Failed to fetch products from dummyjson.');
            return;
        }

        $products = $response->json('products');

        foreach ($products as $product) {

            $category = DB::table('categories')
                ->where('slug', $product['category'])
                ->first();

            $brand = DB::table('brands')
                ->where('name', $product['brand'] ?? 'Unknown')
                ->first();

            $basePrice    = $product['price'];
            $comparePrice = round(
                $basePrice / (1 - ($product['discountPercentage'] ?? 0) / 100),
                2
            );

            DB::table('products')->insert([
                'id'               => Str::uuid(),
                'category_id'      => $category->id ?? null,
                'brand_id'         => $brand->id ?? null,
                'name'             => $product['title'],
                'slug'             => $this->generateUniqueSlug($product['title']),
                'description'      => $product['description'],
                'short_desc'       => Str::limit($product['description'], 200),
                'image_url'        => $product['thumbnail'] ?? null,
                'base_price'       => $basePrice,
                'compare_price'    => $comparePrice,
                'cost_price'       => null,
                'tax_rate'         => 20.00,
                'weight_grams'     => isset($product['weight'])
                                        ? $product['weight'] * 1000
                                        : null,
                'is_active'        => 1,
                'is_featured'      => 0,
                'meta_title'       => $product['title'],
                'meta_description' => Str::limit($product['description'], 300),
                'rating_avg'       => $product['rating'] ?? 0.00,
                'rating_count'     => count($product['reviews'] ?? []),
                'sold_count'       => 0,
                'is_organic'       => 0,
                'created_at'       => now(),
                'updated_at'       => now(),
            ]);
        }

        $this->command->info('✅ Products seeded: ' . count($products));
    }

    /**
     * Generate a unique slug for the products table.
     * If "some-slug" exists → tries "some-slug-2", "some-slug-3", etc.
     */
    private function generateUniqueSlug(string $title): string
    {
        $baseSlug = Str::slug($title);
        $slug     = $baseSlug;
        $counter  = 2;

        while (DB::table('products')->where('slug', $slug)->exists()) {
            $slug = $baseSlug . '-' . $counter;
            $counter++;
        }

        return $slug;
    }
}




// namespace Database\Seeders;

// use App\Models\Brand;
// use App\Models\Category;
// use App\Models\Product;
// use Illuminate\Database\Seeder;
// use Illuminate\Support\Facades\Http;
// use Illuminate\Support\Str;

// class ProductSeeder extends Seeder
// {
//     /**
//      * Run the database seeds.
//      */
//     public function run(): void
//     {
//         $response = Http::get('https://kolzsticks.github.io/Free-Ecommerce-Products-Api/main/products.json');
        
//         if ($response->successful()) {
//             $data = $response->json();
//             foreach ($data as $item) {
//                 $alias=  Str::slug($item['name']);
//                 $alias_parent =  Str::slug($item['category']);
//                 Product::create([
//                     'id' => (string) Str::uuid(),
//                     'brand_id' => (Brand::inRandomOrder()->first())->id,
//                     'category_id' =>(Category::where("slug" , $alias_parent)->first())?->id,
//                     'image_url' => $item['image'],

//                     'name' => $item['name'],
//                     'slug' => $alias,
//                     'description' => $item['description'],
//                     'short_desc' => substr($item['description'], 0, 20),

//                     'base_price' => $item['priceCents'],
//                     'compare_price' => ($item['priceCents'] + ($item['priceCents'] * 0.2)),
//                     'cost_price' => ($item['priceCents'] * 0.2),

//                     'sold_count' => random_int(0,140),
//                     'tax_rate' => random_int(5,50),
//                     'weight_grams' => random_int(1,10),

//                     'is_active' => true,
//                     'is_organic' => random_int(0,1),

//                     'rating_avg' => random_int(1,5),
//                     'rating_count' =>random_int(1,5),
//                 ]);
//             }
//         }

//         Product::all();
//     }
// }
