<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\admin\ProductResource;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Actions\Product\StoreProductAction;
use App\Http\Requests\ProductFileRequest;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        //filter
        $productquery = Product::with(['category', 'brand']);

        if ($request->has('all')) {
            $productquery->withoutGlobalScopes();
        }

        $products = $productquery->paginate(10);
        return response()->json(['response' => ['data' => ProductResource::collection($products->items()), 'pagination' => [
            'total' => $products->total(),
            'per_page' => $products->perPage(),
            'current_page' => $products->currentPage(),
            'last_page' => $products->lastPage(),
        ]]]);
    }
    public function store( ProductFileRequest  $request, StoreProductAction   $action) {

        $product = $action->execute($request->validated());

        return response()->json([
            'product' => $product,
            'message' => 'Product created successfully.',
        ], 201);
        
        
    }
    public function update( ProductFileRequest $request, StoreProductAction $action) {

        $product = $action->execute($request->validated());

        return response()->json([
            'product' => $product,
            'message' => 'Product updated successfully.',
        ], 201);
        
    }
}
