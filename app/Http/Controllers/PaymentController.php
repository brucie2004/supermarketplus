<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Order;
use App\Models\Payment;
use Stripe\Stripe;
use Stripe\PaymentIntent;
use Stripe\Exception\ApiErrorException;

class PaymentController extends Controller
{
    public function createPaymentIntent(Request $request)
    {
        $request->validate([
            'order_id' => 'required|exists:orders,id'
        ]);

        $order = Order::where('id', $request->order_id)
                     ->where('user_id', Auth::id())
                     ->firstOrFail();

        Stripe::setApiKey(config('services.stripe.secret'));

        try {
            $paymentIntent = PaymentIntent::create([
                'amount' => $order->total_amount * 100, // Convert to cents
                'currency' => 'usd',
                'metadata' => [
                    'order_id' => $order->id,
                    'user_id' => Auth::id()
                ],
            ]);

            // Create payment record
            Payment::create([
                'order_id' => $order->id,
                'stripe_payment_intent_id' => $paymentIntent->id,
                'status' => 'pending',
                'amount' => $order->total_amount,
                'currency' => 'usd'
            ]);

            return response()->json([
                'clientSecret' => $paymentIntent->client_secret
            ]);

        } catch (ApiErrorException $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function handlePaymentSuccess(Request $request)
    {
        $request->validate([
            'order_id' => 'required|exists:orders,id'
        ]);

        $order = Order::where('id', $request->order_id)
                     ->where('user_id', Auth::id())
                     ->with('payment')
                     ->firstOrFail();

        Stripe::setApiKey(config('services.stripe.secret'));

        try {
            $paymentIntent = PaymentIntent::retrieve($order->payment->stripe_payment_intent_id);
            
            if ($paymentIntent->status === 'succeeded') {
                // Update payment status
                $order->payment->update([
                    'status' => 'succeeded',
                    'payment_method_details' => $paymentIntent->charges->data[0]->payment_method_details ?? null
                ]);

                // Update order status
                $order->update(['status' => 'processing']);

                // TODO: Send confirmation email
                // TODO: Update inventory

                return redirect()->route('order.confirmation', $order->id)
                                 ->with('success', 'Payment completed successfully!');
            } else {
                return redirect()->route('checkout')
                                 ->with('error', 'Payment failed. Please try again.');
            }

        } catch (ApiErrorException $e) {
            return redirect()->route('checkout')
                             ->with('error', 'Error verifying payment: ' . $e->getMessage());
        }
    }

    public function showConfirmation(Order $order)
    {
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        return view('orders.confirmation', compact('order'));
    }
}