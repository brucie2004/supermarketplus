<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminOrderController extends Controller
{
    /**
     * Display a listing of the orders.
     */
    public function index(Request $request)
    {
        $query = Order::with('user', 'items.product')->latest();
        
        // Filter by status
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }
        
        // Filter by date
        if ($request->has('date') && $request->date != '') {
            $query->whereDate('created_at', $request->date);
        }
        
        // Filter by customer
        if ($request->has('customer') && $request->customer != '') {
            $query->whereHas('user', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->customer . '%')
                  ->orWhere('email', 'like', '%' . $request->customer . '%');
            });
        }
        
        $orders = $query->paginate(15);
        $totalRevenue = Order::where('status', 'completed')->sum('total_amount');
        $pendingOrders = Order::where('status', 'pending')->count();
        
        return view('admin.orders.index', compact('orders', 'totalRevenue', 'pendingOrders'));
    }

    /**
     * Display the specified order.
     */
    public function show(Order $order)
    {
        
            $order->load('user.orders', 'items.product');
            
            
            $shippingAddress = $this->formatAddressForDisplay($order->shipping_address);
            $billingAddress = $order->billing_address ? $this->formatAddressForDisplay($order->billing_address) : null;
            
            return view('admin.orders.show', compact('order', 'shippingAddress', 'billingAddress'));
    }

    /**
     * Update the order status.
     */
    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:pending,processing,shipped,delivered,cancelled,completed'
        ]);
        
        $oldStatus = $order->status;
        $order->update(['status' => $request->status]);
        
        // If order is cancelled, restock the products
        if ($request->status == 'cancelled' && $oldStatus != 'cancelled') {
            foreach ($order->items as $item) {
                $item->product->increment('stock_quantity', $item->quantity);
            }
        }
        
        return redirect()->back()->with('success', 'Order status updated successfully.');
    }

    /**
     * Remove the specified order from storage.
     */
    public function destroy(Order $order)
    {
        // Restock products if order is being deleted
        if ($order->status != 'cancelled') {
            foreach ($order->items as $item) {
                $item->product->increment('stock_quantity', $item->quantity);
            }
        }
        
        $order->items()->delete();
        $order->delete();
        
        return redirect()->route('admin.orders.index')->with('success', 'Order deleted successfully.');
    }
    
    /**
     * Format the address string for display.
     */
    private function formatAddressForDisplay($address)
    {
        if (is_string($address)) {
            return nl2br(e($address));
        }
        
        return $address;
    }
}