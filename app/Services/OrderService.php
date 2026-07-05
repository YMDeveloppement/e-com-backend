<?php


namespace App\Services;

use App\Models\Product;
use Illuminate\Support\Collection;
use App\Models\OrderItem;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class orderService
{
    // private 
    public function getProducts(array $dataValidated)
    {
        $product_ids = array_map(function ($ele) {
            return is_object($ele) ? $ele->id : $ele['id'];
        }, $dataValidated['products']);

        $products = Product::findOrFail($product_ids);
        return $products;
    }
    public function checkProductStock(Collection $products): bool
    {
        return $products->every(function ($product) {
            return $product->sold_count > 0;
        });
    }

    public function calculatePrice(array $dataValidated)
    {
        $total_price = 0;
        $productsCollection = collect($dataValidated['products']);
        $result = $productsCollection->pluck('qty', 'id')->toArray();
        $price_prds = ($dataValidated['_products'])->map(function ($product) use ($result) {
            $qte = $result[$product->id];
            return $product->base_price * $qte;
        });

        $price_delevery = $this->price_mode_delevery($dataValidated['mode_delevery']);

        $total_price = $price_prds->sum() + $price_delevery;
        return [$price_prds, $price_delevery, $total_price];
    }

    public function price_mode_delevery(string $mode = 'free'): float
    {
        $val = 0;
        switch ($mode) {
            case 'free':
                $val =  5.99;
                break;
            case 'express':
                $val =  9.99;
                break;
            case 'overnight':
                $val =  19.99;
                break;
        }

        return $val;
    }


    public function createOrder($data)
    {
        return DB::transaction(function () use ($data) {

            [$products, $_products, $price_prds, $price_delevery, $total_price, $discount] = $data;

            $productsCollection = collect($_products);
            $prds = $productsCollection->pluck('qty', 'id')->toArray();

            $userAuth = Auth::user();

            $new_order  = Order::create([
                "user_id" => $userAuth->id,
                'subtotal' => collect($price_prds)->sum(),
                'tax' => 0,
                'shipping_cost' => $price_delevery,
                'discount' => $discount,
                'total' => $total_price
            ]);


            $order_items = $products->map(function ($product) use ($prds, $new_order) {
                return  OrderItem::create([
                    'order_id' => $new_order->id,
                    'product_id' => $product->id,
                    'quantity' => $prds[$product->id],
                    'unit_price' => $product->base_price,
                    'total_price' => $product->base_price * $prds[$product->id],
                ]);
            });
            return $new_order;
        });
    }

    function valide_order(array $dataValidated)
    {
        $discount = 0;

        $products = $this->getProducts($dataValidated);
        $state = $this->checkProductStock($products);

        if (!$state) {
            $error = \Illuminate\Validation\ValidationException::withMessages([
                'products' => ['Product out of stock !'],
            ]);
            throw $error;
        }

        [$price_prds, $price_delevery, $total_price] = $this->calculatePrice([...$dataValidated, '_products' => $products]);
        $order = $this->createOrder([$products, $dataValidated['products'], $price_prds, $price_delevery, $total_price, $discount]);

        return $order;
    }

    public function make_encaissed() {}






    public function calculateTotalPrice($products) {}
    public function applyDiscount($discounts, $totalPrice) {}

    public function reduceStock($products) {}

    public function createBill($order) {}
}
