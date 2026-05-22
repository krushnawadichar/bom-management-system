<!-- Top Header -->
<div class="top-header">
    <nav class="navbar navbar-expand-lg">
        <div class="container-fluid">
            <button class="btn btn-link text-white menu-toggle d-block d-md-none" id="sidebarToggle">
                <i class="fas fa-bars"></i>
            </button>
            <a class="navbar-brand text-white" href="{{ route('dashboard') }}">
                <i class="fas fa-cubes"></i> BOM Manager
            </a>
            
            <div class="ms-auto">
                <div class="dropdown">
                    <button class="btn btn-link text-white dropdown-toggle" type="button" data-bs-toggle="dropdown">
                        <i class="fas fa-user-circle"></i> {{ Auth::user()->name }}
                        <span class="badge bg-info ms-1">{{ Auth::user()->roles->pluck('name')->implode(', ') }}</span>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="#"><i class="fas fa-user"></i> My Profile</a></li>
                        <li><a class="dropdown-item" href="#"><i class="fas fa-bell"></i> Notifications 
                            @php
                                $notificationCount = Auth::user()->unreadNotifications->count();
                            @endphp
                            @if($notificationCount > 0)
                                <span class="badge bg-danger">{{ $notificationCount }}</span>
                            @endif
                        </a></li>
                        <li><a class="dropdown-item" href="#"><i class="fas fa-cog"></i> Settings</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="dropdown-item">
                                    <i class="fas fa-sign-out-alt"></i> Logout
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>
</div>