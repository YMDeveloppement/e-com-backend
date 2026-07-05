<?php

namespace App\Policies;

use App\Models\Product;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ProductPolicy
{
    public function update(User $user, Product $product): bool
    {
        return $user->id == $product->vendor_id || $user->isAdmin();
    }

}
