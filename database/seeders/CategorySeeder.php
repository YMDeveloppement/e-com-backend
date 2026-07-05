<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $exist_cat = [];
        $parent_cat = [];
        $parent_cat_obj = [];
        $response = Http::get('https://kolzsticks.github.io/Free-Ecommerce-Products-Api/main/products.json');

        if ($response->successful()) {
            $data = $response->json();
            // 2. Loop through and store in the table
            foreach ($data as $item) {
                
                $part = null;
                $alias_parent = Str::slug($item['category']);

                if (!in_array($item['category'], $parent_cat)) {
                    $parent_cat[] = $item['category'];
                    $parent_cat_obj[$alias_parent] = Category::create([
                        'name' => $item['category'],
                        'slug' => $alias_parent,
                        'description' => $item['description'],
                        'image_url' => $item['image'],
                        'is_active' => true,
                    ]);
                }

                $part = $parent_cat_obj[$alias_parent];
                
                $subAlias = Str::slug($item['subCategory']);

                if (!in_array($subAlias, $exist_cat)) {
                    Category::create([
                        'name' => $item['subCategory'],
                        'slug' => Str::slug($item['subCategory']),
                        'description' => $item['description'],
                        'image_url' => $item['image'],
                        'is_active' => true,
                        'parent_id' => $part->id,
                    ]);
                    $exist_cat[] = $subAlias;
                }
            }
        }
    }
}
