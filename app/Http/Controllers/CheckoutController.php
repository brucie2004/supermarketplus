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
            'payment_method' => 'required|in:credit_card,cash_on_delivery',
            'agree_terms' => 'required|accepted',
            'stripe_payment_intent_id' => 'nullable|string', // For Stripe payments
        ]);
        

        // Use database transaction to ensure data consistency
        return DB::transaction(function () use ($request) {
            // Get cart data
            $cartData = $this->getCartData();
            
            if (empty($cartData['cartItems'])) {
                return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
            }
            
            // Determine payment status based on payment method
            $paymentStatus = $request->payment_method === 'cash_on_delivery' ? 'pending' : 'pending';
            $orderStatus = $request->payment_method === 'cash_on_delivery' ? 'confirmed' : 'pending';
            
            // Create order
            $order = Order::create([
                'user_id' => Auth::id(),
                'total_amount' => $cartData['grandTotal'],
                'status' => $orderStatus,
                'shipping_address' => $this->formatAddress($request, 'shipping'),
                'billing_address' => $request->filled('billing_address') ? $this->formatAddress($request, 'billing') : null,
                'payment_method' => $request->payment_method,
                'payment_status' => $paymentStatus,
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

                    // Update product stock (only for cash on delivery, for Stripe we'll update after payment)
                    if ($request->payment_method === 'cash_on_delivery') {
                        $product = Product::find($productId);
                        if ($product) {
                            $product->decrement('stock_quantity', $item['quantity']);
                        }
                    }
                }
            }
            
            // Handle Stripe payment
            if ($request->payment_method === 'credit_card') {
                // For Stripe, we'll redirect to payment processing
                return $this->handleStripePayment($order, $request);
            }
            
            // Clear the cart for cash on delivery
            $this->clearCart();

            // Redirect to order confirmation page for cash on delivery
            return redirect()->route('checkout.confirmation', $order->id)
                ->with('success', 'Order placed successfully! You will pay cash on delivery.');
        });
    }

    /**
     * Handle Stripe payment process
     */
    private function handleStripePayment(Order $order, Request $request)
    {
        try {
            // Set Stripe API key
            \Stripe\Stripe::setApiKey(config('services.stripe.secret'));
            
            // Create a Payment Intent
            $paymentIntent = \Stripe\PaymentIntent::create([
                'amount' => $order->total_amount * 100, // Convert to cents
                'currency' => 'usd',
                'metadata' => [
                    'order_id' => $order->id,
                    'user_id' => Auth::id()
                ],
            ]);

            // Create payment record
            \App\Models\Payment::create([
                'order_id' => $order->id,
                'stripe_payment_intent_id' => $paymentIntent->id,
                'status' => 'requires_payment_method',
                'amount' => $order->total_amount,
                'currency' => 'usd'
            ]);
            
            // Return to payment page with client secret
            return view('checkout.payment', [
                'order' => $order,
                'clientSecret' => $paymentIntent->client_secret,
                'stripeKey' => config('services.stripe.key')
            ]);
            

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Payment processing error: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Clear the user's cart
     */
    private function clearCart()
    {
        if (Auth::check()) {
            DB::table('cart_items')->where('user_id', Auth::id())->delete();
        } else {
            session()->forget('cart');
        }
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


public function processPayment(Order $order, Request $request)
{
    $request->validate([
        'payment_intent_id' => 'required|string'
    ]);

    try {
        \Stripe\Stripe::setApiKey(config('services.stripe.secret'));
        
        // Retrieve the payment intent
        $paymentIntent = \Stripe\PaymentIntent::retrieve($request->payment_intent_id);
        
        if ($paymentIntent->status === 'succeeded') {
            // Update payment record
            $payment = \App\Models\Payment::where('stripe_payment_intent_id', $paymentIntent->id)->first();
            $payment->update([
                'status' => 'succeeded',
                'payment_method_details' => $paymentIntent->charges->data[0]->payment_method_details ?? null
            ]);

            // Update order status
            $order->update([
                'status' => 'processing',
                'payment_status' => 'paid'
            ]);

            // Update product stock
            foreach ($order->items as $item) {
                $product = Product::find($item->product_id);
                if ($product) {
                    $product->decrement('stock_quantity', $item->quantity);
                }
            }

            // Clear the cart
            $this->clearCart();

            return redirect()->route('checkout.confirmation', $order->id)
                ->with('success', 'Payment completed successfully!');
        } else {
            return redirect()->route('checkout.index')
                ->with('error', 'Payment failed. Please try again.');
        }

    } catch (\Exception $e) {
        return redirect()->route('checkout.index')
            ->with('error', 'Payment processing error: ' . $e->getMessage());
    }
}

}