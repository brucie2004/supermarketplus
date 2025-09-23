<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CartController extends Controller
{
    public function index()
    {
        if (auth()->check()) {
            // User is authenticated - get cart from database
            $cartItems = [];
            $dbCartItems = DB::table('cart_items')
                ->where('user_id', auth()->id())
                ->get();
            
            foreach ($dbCartItems as $item) {
                $cartItems[$item->product_id] = [
                    'quantity' => $item->quantity,
                    'added_at' => $item->created_at
                ];
            }
        } else {
            // User is guest - get cart from session
            $cartItems = session()->get('cart', []);
        }
        
        $total = 0;
        
        // Calculate total and get product details
        foreach ($cartItems as $productId => $item) {
            $product = Product::find($productId);
            if ($product) {
                $cartItems[$productId]['product'] = $product;
                $cartItems[$productId]['subtotal'] = $product->price * $item['quantity'];
                $total += $cartItems[$productId]['subtotal'];
            }
        }
        
        return view('cart.index', compact('cartItems', 'total'));
    }

    public function add(Product $product, Request $request)
    {
        $quantity = $request->input('quantity', 1);
        
        if (auth()->check()) {
            // User is authenticated - add to database
            $user = auth()->user();
            $existingCartItem = DB::table('cart_items')
                ->where('user_id', $user->id)
                ->where('product_id', $product->id)
                ->first();
            
            if ($existingCartItem) {
                DB::table('cart_items')
                    ->where('id', $existingCartItem->id)
                    ->update([
                        'quantity' => $existingCartItem->quantity + $quantity,
                        'updated_at' => now()
                    ]);
            } else {
                DB::table('cart_items')->insert([
                    'user_id' => $user->id,
                    'product_id' => $product->id,
                    'quantity' => $quantity,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }
        } else {
            // User is guest - add to session
            $cart = session()->get('cart', []);
            
            if (isset($cart[$product->id])) {
                $cart[$product->id]['quantity'] += $quantity;
            } else {
                $cart[$product->id] = [
                    'quantity' => $quantity,
                    'added_at' => now()->toDateTimeString()
                ];
            }
            
            session()->put('cart', $cart);
        }
        
        return redirect()->route('cart.index')
            ->with('success', 'Product added to cart successfully!');
    }

    public function update(Product $product, Request $request)
    {
        $quantity = $request->input('quantity', 1);
        
        if (auth()->check()) {
            // User is authenticated - update in database
            $user = auth()->user();
            if ($quantity <= 0) {
                DB::table('cart_items')
                    ->where('user_id', $user->id)
                    ->where('product_id', $product->id)
                    ->delete();
            } else {
                DB::table('cart_items')
                    ->where('user_id', $user->id)
                    ->where('product_id', $product->id)
                    ->update(['quantity' => $quantity, 'updated_at' => now()]);
            }
        } else {
            // User is guest - update in session
            $cart = session()->get('cart', []);
            
            if (isset($cart[$product->id])) {
                if ($quantity <= 0) {
                    unset($cart[$product->id]);
                } else {
                    $cart[$product->id]['quantity'] = $quantity;
                }
                
                session()->put('cart', $cart);
            }
        }
        
        return redirect()->route('cart.index')
            ->with('success', 'Cart updated successfully!');
    }

    public function remove(Product $product)
    {
        if (auth()->check()) {
            // User is authenticated - remove from database
            $user = auth()->user();
            DB::table('cart_items')
                ->where('user_id', $user->id)
                ->where('product_id', $product->id)
                ->delete();
        } else {
            // User is guest - remove from session
            $cart = session()->get('cart', []);
            
            if (isset($cart[$product->id])) {
                unset($cart[$product->id]);
                session()->put('cart', $cart);
            }
        }
        
        return redirect()->route('cart.index')
            ->with('success', 'Product removed from cart successfully!');
    }

    public function clear()
    {
        if (auth()->check()) {
            // User is authenticated - clear database cart
            $user = auth()->user();
            DB::table('cart_items')->where('user_id', $user->id)->delete();
        } else {
            // User is guest - clear session cart
            session()->forget('cart');
        }
        
        return redirect()->route('cart.index')
            ->with('success', 'Cart cleared successfully!');
    }
}