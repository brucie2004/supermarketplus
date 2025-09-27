<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Order;
use Illuminate\Http\Request;

class AdminUserController extends Controller
{
    /**
     * Display a listing of the users.
     */
    public function index(Request $request)
    {
        $query = User::withCount('orders')->latest();
        
        // Search functionality
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('email', 'like', '%' . $search . '%');
            });
        }
        
        // Filter by admin status
        if ($request->has('type') && $request->type != '') {
            if ($request->type == 'admin') {
                $query->where('is_admin', true);
            } elseif ($request->type == 'customer') {
                $query->where('is_admin', false);
            }
        }
        
        $users = $query->paginate(15);
        $totalUsers = User::count();
        $adminUsers = User::where('is_admin', true)->count();
        
        return view('admin.users.index', compact('users', 'totalUsers', 'adminUsers'));
    }

    /**
     * Toggle admin status for a user.
     */
  public function toggleAdmin(User $user)
{
    // Prevent removing admin from yourself
    if ($user->id === auth()->id()) {
        return redirect()->back()->with('error', 'You cannot remove admin privileges from yourself.');
    }
    
    // Update the field directly (not mass assignment)
    $user->is_admin = !$user->is_admin;
    $user->save(); // This will save only this specific field
    
    $action = $user->is_admin ? 'promoted to admin' : 'demoted from admin';
    return redirect()->back()->with('success', "User {$user->name} has been {$action} successfully.");
}

    /**
     * Remove the specified user from storage.
     */
    public function destroy(User $user)
    {
        // Prevent deleting yourself
        if ($user->id === auth()->id()) {
            return redirect()->back()->with('error', 'You cannot delete your own account.');
        }
        
        $userName = $user->name;
        $user->delete();
        
        return redirect()->route('admin.users.index')->with('success', "User {$userName} has been deleted successfully.");
    }
}