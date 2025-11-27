<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    public function index(Request $request)
    {
        // Get pagination parameter (default to 10)
        $perPage = $request->input('per_page', 10);
        
        // Get search parameter (default to empty string)
        $search = $request->input('search', '');
        
        // Start query builder
        $query = User::query();
        
        // Apply search filter if search term exists
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('email', 'like', '%' . $search . '%')
                  ->orWhere('role', 'like', '%' . $search . '%')
                  ->orWhere('phone', 'like', '%' . $search . '%');
            });
        }
        
        // Get paginated users
        $users = $query->orderBy('created_at', 'desc')->paginate($perPage);
        
        // Get statistics for all users (not affected by search filter)
        $totalUsers = User::count();
        $totalAdmins = User::where('role', 'admin')->count();
        $totalCashiers = User::where('role', 'cashier')->count();
        
        // Return view with all necessary data
        return view('users.index', compact('users', 'search', 'perPage', 'totalUsers', 'totalAdmins', 'totalCashiers'));
    }

    public function create()
    {
        return view('users.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:admin,cashier',
            'phone' => 'nullable|string|max:15',
            'photo_profile' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        // Handle photo upload
        if ($request->hasFile('photo_profile')) {
            $validated['photo_profile'] = $request->file('photo_profile')->store('profile-photos', 'public');
        }

        $validated['password'] = Hash::make($validated['password']);

        $user = User::create($validated);

        return redirect()->route('users.index')->with('success', 'User created successfully');
    }

    public function show(User $user)
    {
        return view('users.show', compact('user'));
    }

    public function edit(User $user)
    {
        return view('users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
            'role' => 'required|in:admin,cashier',
            'phone' => 'nullable|string|max:15',
            'photo_profile' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        // Handle photo upload
        if ($request->hasFile('photo_profile')) {
            // Delete old photo
            if ($user->photo_profile) {
                Storage::disk('public')->delete($user->photo_profile);
            }
            $validated['photo_profile'] = $request->file('photo_profile')->store('profile-photos', 'public');
        }

        // Only update password if provided
        if ($request->filled('password')) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $user->update($validated);

        return redirect()->route('users.index')->with('success', 'User updated successfully');
    }

    public function destroy(User $user)
    {
        // Delete photo if exists
        if ($user->photo_profile) {
            Storage::disk('public')->delete($user->photo_profile);
        }

        $user->delete();

        return redirect()->route('users.index')->with('success', 'User deleted successfully');
    }
}