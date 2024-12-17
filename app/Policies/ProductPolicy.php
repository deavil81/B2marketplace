<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Product;
use Illuminate\Auth\Access\HandlesAuthorization;

class ProductPolicy
{
    use HandlesAuthorization;

    /**
     * Determine if the given user can view the product.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Product  $product
     * @return bool
     */
    public function view(User $user, Product $product)
    {
        // Example: Only allow if the user is the owner of the product
        return $user->id === $product->user_id;
    }

    /**
     * Determine if the given user can create a product.
     *
     * @param  \App\Models\User  $user
     * @return bool
     */
    public function create(User $user)
    {
        // Example: Allow any logged-in user to create a product
        return $user->is_logged_in;
    }

    /**
     * Determine if the given user can update the product.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Product  $product
     * @return bool
     */
    public function update(User $user, Product $product)
    {
        // Example: Only allow the product owner to update the product
        return $user->id === $product->user_id;
    }

    /**
     * Determine if the given user can delete the product.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Product  $product
     * @return bool
     */
    public function delete(User $user, Product $product)
    {
        // Example: Only allow the product owner to delete the product
        return $user->id === $product->user_id;
    }
}
