<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class CheckoutController extends Controller
{
    public function index()
    {
        // Get cart items and calculate total
        $cartData = $this->getCartData();
        
        if ($cartData['total'] == 0) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }
        
        return view('checkout.index', $cartData);
    }

    public function process(Request $request)
{
    // Validate the request
    $request->validate([
        'shipping_first_name' => 'required|string|max:255',
        'shipping_last_name' => 'required|string|max:255',
        'shipping_email' => 'required|email',
        'shipping_phone' => 'required|string|max:20',
        'shipping_address' => 'required|string|max:500',
        'shipping_city' => 'required|string|max:255',
        'shipping_state' => 'required|string|max:255',
        'shipping_postal_code' => 'required|string|max:20',
        'shipping_country' => 'required|string|max:255',
        'billing_first_name' => 'nullable|string|max:255',
        'billing_last_name' => 'nullable|string|max:255',
        'billing_email' => 'nullable|email',
        'billing_phone' => 'nullable|string|max:20',
        'billing_address' => 'nullable|string|max:500',
        'billing_city' => 'nullable|string|max:255',
        'billing_state' => 'nullable|string|max:255',
        'billing_postal_code' => 'nullable|string|max:20',
        'billing_country' => 'nullable|string|max:255',
        'payment_method' => 'required|in:credit_card,paypal,cash_on_delivery',
        'agree_terms' => 'required|accepted',
    ]);

    // Use database transaction to ensure data consistency
    return DB::transaction(function () use ($request) {
        // Get cart data
        $cartData = $this->getCartData();
        
        if (empty($cartData['cartItems'])) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }
        
        // Create order
        $order = Order::create([
            'user_id' => Auth::id(),
            'total_amount' => $cartData['grandTotal'],
            'status' => 'pending',
            'shipping_address' => $this->formatAddress($request, 'shipping'),
            'billing_address' => $request->filled('billing_address') ? $this->formatAddress($request, 'billing') : null,
            'payment_method' => $request->payment_method,
            'payment_status' => 'pending',
            'shipping_method' => 'standard',
        ]);

        // Create order items
        foreach ($cartData['cartItems'] as $productId => $item) {
            if (isset($item['product'])) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $productId,
                    'quantity' => $item['quantity'],
                    'price' => $item['product']->price,
                    'total' => $item['product']->price * $item['quantity'],
                ]);

                // Update product stock
                $product = Product::find($productId);
                if ($product) {
                    $product->decrement('stock_quantity', $item['quantity']);
                }
            }
        }

        // Clear the cart
        if (Auth::check()) {
            DB::table('cart_items')->where('user_id', Auth::id())->delete();
        } else {
            session()->forget('cart');
        }

        // Redirect to order confirmation page
        return redirect()->route('checkout.confirmation', $order->id)
            ->with('success', 'Order placed successfully!');
    });
}

    public function confirmation($orderId)
    {
        $order = Order::with('items.product')->findOrFail($orderId);
        
        // Verify that the order belongs to the authenticated user
        if (Auth::id() !== $order->user_id) {
            abort(403);
        }

        return view('checkout.confirmation', compact('order'));
    }

    private function getCartData()
    {
        if (Auth::check()) {
            // User is authenticated - get cart from database
            $cartItems = [];
            $dbCartItems = DB::table('cart_items')
                ->where('user_id', Auth::id())
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
        $shipping = 5.00;
        
        // Calculate total and get product details
        foreach ($cartItems as $productId => $item) {
            $product = Product::find($productId);
            if ($product) {
                $cartItems[$productId]['product'] = $product;
                $cartItems[$productId]['subtotal'] = $product->price * $item['quantity'];
                $total += $cartItems[$productId]['subtotal'];
            }
        }
        
        $tax = $total * 0.1;
        $grandTotal = $total + $shipping + $tax;
        
        return [
            'cartItems' => $cartItems,
            'total' => $total,
            'shipping' => $shipping,
            'tax' => $tax,
            'grandTotal' => $grandTotal
        ];
    }

    private function formatAddress($request, $type)
{
    // Format the address as a string instead of JSON
    $firstName = $request->input("{$type}_first_name");
    $lastName = $request->input("{$type}_last_name");
    $address = $request->input("{$type}_address");
    $city = $request->input("{$type}_city");
    $state = $request->input("{$type}_state");
    $postalCode = $request->input("{$type}_postal_code");
    $country = $request->input("{$type}_country");
    $email = $request->input("{$type}_email");
    $phone = $request->input("{$type}_phone");
    
    return "{$firstName} {$lastName}\n{$address}\n{$city}, {$state} {$postalCode}\n{$country}\nEmail: {$email}\nPhone: {$phone}";
}
}