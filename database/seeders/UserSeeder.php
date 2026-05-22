<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Admin User
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@bom.com',
            'password' => Hash::make('password'),
            'department' => 'IT',
            'employee_id' => 'ADM001'
        ]);
        $admin->assignRole('admin');
        
        // Purchase Department User
        $purchase = User::create([
            'name' => 'John Purchase',
            'email' => 'purchase@bom.com',
            'password' => Hash::make('password'),
            'department' => 'Purchase',
            'employee_id' => 'PUR001'
        ]);
        $purchase->assignRole('purchase-dept');
        
        // Engineer User
        $engineer = User::create([
            'name' => 'Sarah Engineer',
            'email' => 'engineer@bom.com',
            'password' => Hash::make('password'),
            'department' => 'Engineering',
            'employee_id' => 'ENG001'
        ]);
        $engineer->assignRole('engineer');
        
        // Store Manager User
        $store = User::create([
            'name' => 'Mike Store',
            'email' => 'store@bom.com',
            'password' => Hash::make('password'),
            'department' => 'Store',
            'employee_id' => 'STR001'
        ]);
        $store->assignRole('store-manager');
    }
}