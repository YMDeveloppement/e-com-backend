<?php

namespace App\Actions\Product;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class StoreProductAction
{
    /**
     * Execute the action.
     *
     * @param  array  $data  Validated data from StoreProductRequest::validated()
     * @return Product
     */
    public function execute(array $data): Product
    {
        return DB::transaction(function () use ($data) {
            $product = $this->createProduct($data);
            $this->storeImages($product, $data['images'] ?? []);
            $this->syncCategories($product, $data['category_id'] ?? []);

            $this->syncVariants($product, $data);
            return $product->load(['category', 'brand', 'images']);
            });
    }

    // ── Private helpers ───────────────────────────────────────────────────────

    private function createProduct(array $data): Product
    {

        $product = Product::create([
            'id'               => Str::uuid(),
            'brand_id'         => $data['brand_id'],
            'name'             => $data['name'],
            'slug'             => $data['slug'],
            'description'      => $data['description']      ?? null,
            'short_desc'       => $data['short_desc']       ?? null,
            'image_url'        => $data['image_url']        ?? null,
            'base_price'       => $data['base_price'],
            'compare_price'    => $data['compare_price']    ?? null,
            'cost_price'       => $data['cost_price']       ?? null,
            'tax_rate'         => $data['tax_rate']         ?? 20.00,
            'weight_grams'     => $data['weight_grams']     ?? null,
            'is_active'        => $data['is_active']        ?? true,
            'is_featured'      => $data['is_featured']      ?? false,
            'is_organic'       => $data['is_organic']       ?? false,
            'meta_title'       => $data['meta_title']       ?? null,
            'meta_description' => $data['meta_description'] ?? null,
            'schedule'         => $data['schedule']         ?? null,
        ]);
        return $product;
    }

    /**
     * Sync many-to-many categories.
     * Assumes a pivot table: category_product (category_id, product_id)
     */
    private function syncCategories(Product $product, array $categoryIds): void
    {
        if (!empty($categoryIds) && isset($categoryIds[0])) {
            $product->category()->associate($categoryIds[0])->save();
        }
    }

    /**
     * Store colors and sizes as product variants.
     * Assumes a product_variants table or JSON columns — adjust to your schema.
     */
    private function syncVariants(Product $product, array $data): void
    {
        $colors = $data['colors'] ?? [];
        $sizes  = $data['sizes']  ?? [];

        if (empty($colors) && empty($sizes)) {
            return;
        }

        // Build all color × size combinations
        $variants = [];

        $colorList = !empty($colors) ? $colors : [null];
        $sizeList  = !empty($sizes)  ? $sizes  : [null];

        foreach ($colorList as $color) {
            foreach ($sizeList as $size) {
                $variants[] = [
                    'product_id' => $product->id,
                    'color'      => $color,
                    'size'       => $size,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        // Bulk insert for performance
        $product->variants()->delete();   // clear old variants first
        DB::table('product_variants')->insert($variants);
    }

    /**
     * Upload image files and store references in product_images table.
     * Files are stored under storage/app/public/products/{product_id}/
     */
    private function storeImages(Product $product, array $images)
    {
        if (empty($images)) {
            return;
        }

        $folder  = "products/{$product->id}";
        $records = [];

        foreach ($images as $index => $file) {

            $path = $file->store($folder, 'public');

            $record = [
            'imageable_id' => $product->id,
            'imageable_type' => Product::class,
            'url'        => Storage::url($path),
            'path'       => $path,
            'sort_order' => $index,
            'main_img' => $index === 0 ? 1 : 0,
            ];
            $records[] = $record;
            $product->images()->create($record);
        }

        // Set first image as main image_url if not provided
        if (empty($product->image_url) && !empty($records)) {
            $product->update(['image_url' => $records[0]['url']]);
        }
        return $product->images();
    }
}
