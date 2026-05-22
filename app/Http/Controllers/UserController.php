<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    /**
     * Display a listing of users.
     */
    public function index(Request $request)
    {
        $users = User::with('roles')->paginate(10);
        return view('users.index', compact('users'));
    }
    
    /**
     * Show the form for creating a new user.
     */
    public function create()
    {
        $roles = Role::all();
        return view('users.create', compact('roles'));
    }
    
    /**
     * Store a newly created user.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'department' => 'nullable|string|max:255',
            'employee_id' => 'nullable|string|max:255|unique:users',
            'role' => 'required|exists:roles,name'
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'department' => $request->department,
            'employee_id' => $request->employee_id
        ]);
        
        // Assign role
        $user->assignRole($request->role);
        
        return redirect()->route('users.index')
            ->with('success', 'User created successfully.');
    }
    
    /**
     * Display the specified user.
     */
    public function show(User $user)
    {
        return view('users.show', compact('user'));
    }
    
    /**
     * Show the form for editing the specified user.
     */
    public function edit(User $user)
    {
        $roles = Role::all();
        $userRole = $user->roles->first();
        
        return view('users.edit', compact('user', 'roles', 'userRole'));
    }
    
    /**
     * Update the specified user.
     */
    public function update(Request $request, User $user)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'department' => 'nullable|string|max:255',
            'employee_id' => 'nullable|string|max:255|unique:users,employee_id,' . $user->id,
            'role' => 'required|exists:roles,name'
        ]);
        
        if ($request->filled('password')) {
            $validator->addRules([
                'password' => 'string|min:8|confirmed'
            ]);
        }
        
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        
        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'department' => $request->department,
            'employee_id' => $request->employee_id
        ]);
        
        if ($request->filled('password')) {
            $user->update(['password' => Hash::make($request->password)]);
        }
        
        // Sync role
        $user->syncRoles([$request->role]);
        
        return redirect()->route('users.index')
            ->with('success', 'User updated successfully.');
    }
    
    /**
     * Remove the specified user.
     */
    public function destroy(User $user)
    {
        // Prevent admin from deleting themselves
        if ($user->id === auth()->id()) {
            return redirect()->route('users.index')
                ->with('error', 'You cannot delete your own account.');
        }
        
        $user->delete();
        
        return redirect()->route('users.index')
            ->with('success', 'User deleted successfully.');
    }
}