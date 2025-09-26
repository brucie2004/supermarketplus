<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\Category;
use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function dashboard()
{
    $stats = [
        'totalProducts' => Product::count(),
        'totalCategories' => Category::count(),
        'totalOrders' => Order::count(),
        'totalUsers' => User::count(),
        'pendingOrders' => Order::where('status', 'pending')->count(),
        'completedOrders' => Order::where('status', 'completed')->count(),
        'revenue' => Order::where('status', 'completed')->sum('total_amount'),
    ];

    $recentOrders = Order::with('user')->latest()->take(5)->get();
    $lowStockProducts = Product::where('stock_quantity', '<', 10)->get();

    return view('admin.dashboard', compact('stats', 'recentOrders', 'lowStockProducts'));
}
}