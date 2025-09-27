@extends('admin.layout')

@section('title', 'User Management')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">User Management</h3>
                    
                    <div class="card-tools">
                        <div class="row">
                            <div class="col">
                                <form method="GET" action="{{ route('admin.users.index') }}">
                                    <div class="input-group input-group-sm" style="width: 200px;">
                                        <input type="text" name="search" class="form-control float-right" placeholder="Search users..." value="{{ request('search') }}">
                                        <div class="input-group-append">
                                            <button type="submit" class="btn btn-default">
                                                <i class="fas fa-search"></i>
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <!-- Statistics Cards -->
                    <div class="row">
                        <div class="col-lg-3 col-6">
                            <div class="small-box bg-info">
                                <div class="inner">
                                    <h3>{{ $totalUsers }}</h3>
                                    <p>Total Users</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-users"></i>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-6">
                            <div class="small-box bg-success">
                                <div class="inner">
                                    <h3>{{ $adminUsers }}</h3>
                                    <p>Admin Users</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-user-shield"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Filters -->
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <div class="btn-group">
                                <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary {{ !request('type') ? 'active' : '' }}">All Users</a>
                                <a href="{{ route('admin.users.index', ['type' => 'admin']) }}" class="btn btn-outline-secondary {{ request('type') == 'admin' ? 'active' : '' }}">Admins</a>
                                <a href="{{ route('admin.users.index', ['type' => 'customer']) }}" class="btn btn-outline-secondary {{ request('type') == 'customer' ? 'active' : '' }}">Customers</a>
                            </div>
                        </div>
                    </div>

                    <!-- Users Table -->
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>User</th>
                                    <th>Email</th>
                                    <th>Orders</th>
                                    <th>Status</th>
                                    <th>Joined</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($users as $user)
                                <tr>
                                    <td>{{ $user->id }}</td>
                                    <td>
                                        <div class="user-info">
                                            <div class="user-name">{{ $user->name }}</div>
                                            @if($user->is_admin)
                                                <span class="badge badge-success">Admin</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td>{{ $user->email }}</td>
                                    <td>
                                        <span class="badge badge-primary">{{ $user->orders_count }} orders</span>
                                    </td>
                                    <td>
                                        <span class="badge badge-success">Active</span>
                                    </td>
                                    <td>{{ $user->created_at->format('M j, Y') }}</td>
                                    <td>
                                        <div class="btn-group">
                                            @if($user->id !== auth()->id())
                                                <form action="{{ route('admin.users.toggle-admin', $user) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('PUT')
                                                    <button type="submit" class="btn btn-sm {{ $user->is_admin ? 'btn-warning' : 'btn-success' }}">
                                                        {{ $user->is_admin ? 'Remove Admin' : 'Make Admin' }}
                                                    </button>
                                                </form>
                                                
                                                <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this user?')">
                                                        Delete
                                                    </button>
                                                </form>
                                            @else
                                                <span class="text-muted">Current User</span>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="card-footer clearfix">
                    {{ $users->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection