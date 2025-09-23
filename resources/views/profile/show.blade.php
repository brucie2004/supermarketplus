<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile | SuperMarketPlus</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
</head>
<body>
    @include('partials.navbar')
    
    <div class="container my-4">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">My Profile</li>
            </ol>
        </nav>

        <div class="row">
            <!-- Sidebar Navigation -->
            <div class="col-md-3">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">My Account</h5>
                    </div>
                    <div class="list-group list-group-flush">
                        <a href="{{ route('profile.show') }}" class="list-group-item list-group-item-action {{ request()->routeIs('profile.show') ? 'active' : '' }}">
                            <i class="bi bi-person"></i> Profile Overview
                        </a>
                        <a href="{{ route('profile.orders') }}" class="list-group-item list-group-item-action {{ request()->routeIs('profile.orders') ? 'active' : '' }}">
                            <i class="bi bi-bag"></i> Order History
                        </a>
                        <a href="{{ route('profile.edit') }}" class="list-group-item list-group-item-action {{ request()->routeIs('profile.edit') ? 'active' : '' }}">
                            <i class="bi bi-gear"></i> Account Settings
                        </a>
                    </div>
                </div>
            </div>
            
            <!-- Main Content -->
            <div class="col-md-9">
                <div class="card">
                    <div class="card-header">
                        <h4 class="mb-0">Profile Overview</h4>
                    </div>
                    <div class="card-body">
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <h5>Personal Information</h5>
                                <p><strong>Name:</strong> {{ $user->name }}</p>
                                <p><strong>Email:</strong> {{ $user->email }}</p>
                                <p><strong>Member Since:</strong> {{ $user->created_at->format('F j, Y') }}</p>
                            </div>
                            <div class="col-md-6">
                                <h5>Account Statistics</h5>
                                @php
                                    $totalOrders = App\Models\Order::where('user_id', $user->id)->count();
                                    $totalSpent = App\Models\Order::where('user_id', $user->id)->sum('total_amount');
                                @endphp
                                <p><strong>Total Orders:</strong> {{ $totalOrders }}</p>
                                <p><strong>Total Spent:</strong> ${{ number_format($totalSpent, 2) }}</p>
                                <p><strong>Last Login:</strong> {{ $user->updated_at->format('F j, Y g:i A') }}</p>
                            </div>
                        </div>
                        
                        <hr>
                        
                        <h5 class="mb-3">Recent Orders</h5>
                        @if($recentOrders->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Order #</th>
                                            <th>Date</th>
                                            <th>Status</th>
                                            <th>Total</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($recentOrders as $order)
                                            <tr>
                                                <td>#{{ $order->id }}</td>
                                                <td>{{ $order->created_at->format('M j, Y') }}</td>
                                                <td>
                                                    <span class="badge 
                                                        @if($order->status == 'completed') bg-success
                                                        @elseif($order->status == 'pending') bg-warning
                                                        @elseif($order->status == 'cancelled') bg-danger
                                                        @else bg-secondary @endif">
                                                        {{ ucfirst($order->status) }}
                                                    </span>
                                                </td>
                                                <td>${{ number_format($order->total_amount, 2) }}</td>
                                                <td>
                                                    <a href="{{ route('profile.order.details', $order) }}" class="btn btn-sm btn-outline-primary">
                                                        View Details
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="text-center mt-3">
                                <a href="{{ route('profile.orders') }}" class="btn btn-primary">View All Orders</a>
                            </div>
                        @else
                            <div class="text-center py-4">
                                <i class="bi bi-bag-x" style="font-size: 3rem;"></i>
                                <p class="mt-2">You haven't placed any orders yet.</p>
                                <a href="{{ route('home') }}" class="btn btn-primary">Start Shopping</a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    @include('partials.footer')
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>