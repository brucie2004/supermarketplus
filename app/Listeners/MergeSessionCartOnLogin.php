<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Login;
use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class MergeSessionCartOnLogin
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(object $event): void
    {
  $user = $event->user;
        $sessionCart = session()->get('cart', []);
        
        if (!empty($sessionCart)) {
            foreach ($sessionCart as $productId => $item) {
                // Check if the product already exists in the user's cart
                $existingCartItem = DB::table('cart_items')
                    ->where('user_id', $user->id)
                    ->where('product_id', $productId)
                    ->first();
                
                if ($existingCartItem) {
                    // Update quantity if product already in cart
                    DB::table('cart_items')
                        ->where('id', $existingCartItem->id)
                        ->update([
                            'quantity' => $existingCartItem->quantity + $item['quantity'],
                            'updated_at' => now()
                        ]);
                } else {
                    // Add new item to cart
                    DB::table('cart_items')->insert([
                        'user_id' => $user->id,
                        'product_id' => $productId,
                        'quantity' => $item['quantity'],
                        'created_at' => now(),
                        'updated_at' => now()
                    ]);
                }
            }
            
            // Clear the session cart after merging
            session()->forget('cart');
        }

    }
}
