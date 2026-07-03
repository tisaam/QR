<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class AdminUserController extends Controller
{
    public function index()
    {
        $users = User::where('role', '!=', 'super_admin')->with('business')->latest()->paginate(15);
        return view('admin.users.index', compact('users'));
    }

    public function toggleStatus(User $user)
    {
        $newStatus = $user->status === 'active' ? 'suspended' : 'active';
        $user->update(['status' => $newStatus]);
        return back()->with('success', 'User status updated to ' . $newStatus);
    }
}