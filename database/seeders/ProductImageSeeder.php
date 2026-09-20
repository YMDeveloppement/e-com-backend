<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductImageSeeder extends Seeder
{
    public function run(): void
    {

        $products_imgs = [
            'brand.jpg',
            'legume.jpg',
            'shoose.jpg',
            't-shirt.jpeg',
            'oil.png',
            'whey.jpg',
        ];

        
        $index = 0;
        foreach (Product::all() as $product) {
            if($index > 5) {
                $index = 0; // Reset index if it exceeds the array length
            }
            $filename = $products_imgs[$index];

            // Local source image
            $sourcePath = 'C:\Users\dell\Documents\e-com-project\e-com-frontend\public\images\\' . $filename;
            if (!file_exists($sourcePath)) {
                $this->command->error("File not found: {$sourcePath}");
                continue;
            }

            $destination = 'products/' . $filename;

            Storage::disk('public')->put(
                $destination,
                file_get_contents($sourcePath)
            );  

            // Copy image to storage
            $path = 'products/' . $filename;
           

            // Create image relation
            $product->update([
                'image_url' => $path,
            ]);

            $this->command->info(
                "Product created: {$product->name}"
            );

            $this->command->info(
                "Image saved: {$path}"
            );
            
            $index++;
        }
    }
}