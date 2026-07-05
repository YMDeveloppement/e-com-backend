<?php
namespace Database\Seeders;
use App\Models\Brand;
use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductBrandSeeder extends Seeder
{
    public function run(): void
    {
        $brandIds = Brand::pluck('id')->toArray();

        Product::chunk(500, function ($products) use ($brandIds) {

            foreach ($products as $product) {

                $product->brand_id = $brandIds[array_rand($brandIds)];

                $product->saveQuietly();
            }
        });
    }
}