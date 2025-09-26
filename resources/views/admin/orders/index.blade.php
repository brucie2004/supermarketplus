@extends('admin.layout')

@section('title', 'Manage Orders')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2>Order Management</h2>
        <p class="text-muted mb-0">Total Revenue: <strong>${{ number_format($totalRevenue, 2) }}</strong> | Pending Orders: <span class="badge bg-warning">{{ $pendingOrders }}</span></p>
    </div>
</div>

<div class="card shadow">
    <div class="card-body">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- Filters -->
        <form method="GET" action="{{ route('admin.orders.index') }}" class="mb-4">
            <div class="row g-3">
                <div class="col-md-3">
                    <label for="status" class="form-label">Status</label>
                    <select class="form-select" id="status" name="status">
                        <option value="">All Statuses</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="processing" {{ request('status') == 'processing' ? 'selected' : '' }}>Processing</option>
                        <option value="shipped" {{ request('status') == 'shipped' ? 'selected' : '' }}>Shipped</option>
                        <option value="delivered" {{ request('status') == 'delivered' ? 'selected' : '' }}>Delivered</option>
                        <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                        <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="date" class="form-label">Order Date</label>
                    <input type="date" class="form-control" id="date" name="date" value="{{ request('date') }}">
                </div>
                <div class="col-md-3">
                    <label for="customer" class="form-label">Customer</label>
                    <input type="text" class="form-control" id="customer" name="customer" value="{{ request('customer') }}" placeholder="Name or email">
                </div>
                <div class="col-md-3">
                    <label class="form-label">&nbsp;</label>
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary">Filter</button>
                    </div>
                </div>
            </div>
        </form>

        @if($orders->count() > 0)
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Order ID</th>
                            <th>Customer</th>
                            <th>Date</th>
                            <th>Items</th>
                            <th>Total</th>
                            <th>Status</th>
                            <th>Payment</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($orders as $order)
                        <tr>
                            <td>
                                <strong>#{{ $order->id }}</strong>
                            </td>
                            <td>
                                <div>
                                    <strong>{{ $order->user->name }}</strong>
                                    <br>
                                    <small class="text-muted">{{ $order->user->email }}</small>
                                </div>
                            </td>
                            <td>
                                {{ $order->created_at->format('M j, Y') }}
                                <br>
                                <small class="text-muted">{{ $order->created_at->format('g:i A') }}</small>
                            </td>
                            <td>
                                <span class="badge bg-primary">{{ $order->items->count() }}</span>
                            </td>
                            <td>
                                <strong>${{ number_format($order->total_amount, 2) }}</strong>
                            </td>
                            <td>
                                <span class="badge 
                                    @if($order->status == 'completed') bg-success
                                    @elseif($order->status == 'pending') bg-warning
                                    @elseif($order->status == 'cancelled') bg-danger
                                    @elseif($order->status == 'shipped') bg-info
                                    @elseif($order->status == 'delivered') bg-primary
                                    @else bg-secondary @endif">
                                    {{ ucfirst($order->status) }}
                                </span>
                            </td>
                            <td>
                                <span class="badge bg-light text-dark">
                                    {{ ucfirst(str_replace('_', ' ', $order->payment_method)) }}
                                </span>
                                <br>
                                <small class="text-muted">{{ ucfirst($order->payment_status) }}</small>
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('admin.orders.show', $order) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-eye"></i> View
                                    </a>
                                    <form action="{{ route('admin.orders.destroy', $order) }}" method="POST" 
                                          onsubmit="return confirm('Are you sure you want to delete this order? This action cannot be undone.')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <div class="d-flex justify-content-center mt-4">
                {{ $orders->links() }}
            </div>
        @else
            <div class="text-center py-5">
                <i class="bi bi-receipt" style="font-size: 4rem;"></i>
                <h3 class="mt-3">No orders found</h3>
                <p class="text-muted">No orders match your current filters.</p>
                <a href="{{ route('admin.orders.index') }}" class="btn btn-primary mt-2">Clear Filters</a>
            </div>
        @endif
    </div>
</div>
@endsection