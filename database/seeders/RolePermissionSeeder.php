<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
        
        // Define permissions
        $permissions = [
            // BOM permissions
            'view-boms', 'upload-boms', 'delete-boms',
            // Purchase Intent permissions
            'view-purchase-intents', 'manage-purchase-intents',
            // Allocation permissions
            'view-allocations', 'manage-allocations',
            // Inventory permissions
            'view-inventory', 'manage-inventory',
            // Project permissions
            'view-projects', 'manage-projects',
            // Admin permissions
            'view-audit-logs', 'manage-users'
        ];
        
        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }
        
        // Define roles
        $adminRole = Role::create(['name' => 'admin']);
        $purchaseRole = Role::create(['name' => 'purchase-dept']);
        $engineerRole = Role::create(['name' => 'engineer']);
        $storeRole = Role::create(['name' => 'store-manager']);
        
        // Assign all permissions to admin
        $adminRole->givePermissionTo(Permission::all());
        
        // Assign permissions to purchase department
        $purchaseRole->givePermissionTo([
            'view-purchase-intents', 'manage-purchase-intents',
            'view-boms', 'view-allocations'
        ]);
        
        // Assign permissions to engineer
        $engineerRole->givePermissionTo([
            'view-boms', 'upload-boms', 'view-allocations', 'view-inventory'
        ]);
        
        // Assign permissions to store manager
        $storeRole->givePermissionTo([
            'view-inventory', 'manage-inventory', 'view-allocations', 'manage-allocations'
        ]);
    }
}