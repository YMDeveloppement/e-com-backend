<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\Product;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class ProductImageSeesssssder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        
        foreach (Product::where('image_url', 'not like', '%products/%')->where('image_url', '!=', null)->get() as $product) {
            try{

            
            $response = Http::timeout(30)->get($product->image_url);
            if (!$response->successful()) {
                $this->command->warn(
                    "Image download failed: {$product->image_url}"
                );
                continue;
            }

            // 3. Detect extension
            $contentType = $response->header('Content-Type');

            $extension = match ($contentType) {
                'image/jpeg' => 'jpg',
                'image/png'  => 'png',
                'image/webp' => 'webp',
                'image/gif'  => 'gif',
                default      => 'jpg',
            };

            // 4. Generate unique filename
            $filename = Str::uuid() . '.' . $extension;

            // 5. Save image in storage/app/public/products
            $path = "products/{$filename}";

            Storage::disk('public')->put(
                $path,
                $response->body()
            );

            $product->update([
                'image_url' => $path,
            ]);
            dd($path);

            }catch(\Exception $e){
                $this->command->warn(
                    "Image download failed: {$product->image_url} - Error: {$e->getMessage()}"
                );
                continue;
            }

        }
    }
}
