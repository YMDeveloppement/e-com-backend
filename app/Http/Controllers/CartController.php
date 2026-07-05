<?php

namespace App\Http\Controllers;

use App\Http\Resources\CartResource;
use App\Models\CartItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    function  index()
    {
        $cart = CartItem::all();
        return response()->json([
            "cartProducts" => $cart
        ]);
    }

    public function store(Request $request)
    {

        dd($request->all());

        dd($request->user_id);


        $request->validate([
            'items' => 'required|array',
            'items.*.product_id' => 'required|integer',
            'items.*.quantity' => 'required|integer',
        ]);

        foreach ($request->items as $item) {
            CartItem::create([
                'user_id' => $request->user_id,
                'product_id' => $item['product_id'],
                'quantity' => $item['quantity'],
            ]);
        }

        $cart = CartItem::all();
        return response()->json([
            "cartProducts" => $cart
        ]);
    }
    public function sync_cart(Request $request)
    {
        $user = Auth::user();
        $id_product = [];

        if ($request->has('items') && is_array($request->items) && !empty($request->items)) {
            foreach ($request->items as $item) {
                $id_product[] = $item['id'];
                CartItem::updateOrCreate([
                    'user_id' => $user->id,
                    'product_id' => $item['id'],
                ], [
                    'qty' => $item['qty'],
                ]);
            }

            CartItem::where('user_id', $user->id)
                ->whereNotIn('product_id', $id_product)
                ->delete();

        }
        
        $cartItems = CartItem::with('product')->get();
        return response()->json(CartResource::collection($cartItems));
    }
}
