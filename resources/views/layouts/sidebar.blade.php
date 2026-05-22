<!-- Sidebar -->
<div class="sidebar" id="sidebar">
    <div class="nav-section">
    
        
        <div class="nav-section-title">MAIN NAVIGATION</div>
        
        <div class="nav-item">
            <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                <i class="fas fa-chart-line"></i>
                <span>Dashboard</span>
            </a>
        </div>
        
        <div class="nav-section-title">BOM MANAGEMENT</div>
        
        <div class="nav-item">
            <a class="nav-link {{ request()->routeIs('boms.*') ? 'active' : '' }}" href="{{ route('boms.index') }}">
                <i class="fas fa-file-alt"></i>
                <span>Bill of Materials</span>
            </a>
        </div>
        
        <div class="nav-item">
            <a class="nav-link {{ request()->routeIs('boms.create') ? 'active' : '' }}" href="{{ route('boms.create') }}">
                <i class="fas fa-upload"></i>
                <span>Upload BOM</span>
            </a>
        </div>
        
        @can('view-purchase-intents')
        <div class="nav-item">
            <a class="nav-link {{ request()->routeIs('purchase-intents.*') ? 'active' : '' }}" href="{{ route('purchase-intents.index') }}">
                <i class="fas fa-shopping-cart"></i>
                <span>Purchase Intents</span>
                <span class="badge bg-danger" id="pending-intents-badge" style="display: none;">0</span>
            </a>
        </div>
        @endcan
        
        <div class="nav-item">
            <a class="nav-link {{ request()->routeIs('allocations.*') ? 'active' : '' }}" href="{{ route('allocations.index') }}">
                <i class="fas fa-truck"></i>
                <span>Material Allocations</span>
            </a>
        </div>
        
        @can('view-inventory')
        <div class="nav-item">
            <a class="nav-link {{ request()->routeIs('inventory.*') ? 'active' : '' }}" href="{{ route('inventory.index') }}">
                <i class="fas fa-boxes"></i>
                <span>Inventory Management</span>
            </a>
        </div>
    
        @endcan
    
         <!-- Users Menu - Only for Admin -->
        @role('admin')
        <div class="nav-section-title">USER MANAGEMENT</div>
        
        <div class="nav-item">
            <a class="nav-link {{ request()->routeIs('users.*') ? 'active' : '' }}" href="{{ route('users.index') }}">
                <i class="fas fa-users"></i>
                <span>Users</span>
            </a>
        </div>
        @endrole
        <div class="nav-section-title">SYSTEM</div>
        
        <div class="nav-item">
            <a class="nav-link" href="#">
                <i class="fas fa-history"></i>
                <span>Audit Logs</span>
            </a>
        </div>

        <!-- System Status -->
        <div class="system-status mt-4 p-3 border-top">
            <div class="d-flex align-items-center justify-content-between">
                <span class="text-muted small">System Status</span>
                <span class="badge bg-success">Online</span>
            </div>
            <div class="progress mt-2" style="height: 4px;">
                <div class="progress-bar" role="progressbar" style="width: 75%"></div>
            </div>
            <div class="d-flex justify-content-between mt-2">
                <span class="small">Storage: 75%</span>
                <span class="small">DB: Active</span>
            </div>
        </div>
    </div>
</div>