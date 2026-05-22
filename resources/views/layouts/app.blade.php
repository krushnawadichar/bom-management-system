<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>BOM Management System - @yield('title')</title>
    
    <!-- CSS Dependencies -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Custom CSS -->
    @include('layouts.partials.styles')
    
    @stack('styles')
</head>
<body>
    <!-- Header -->
    @include('layouts.header')
    
    <!-- Sidebar -->
    @include('layouts.sidebar')
    
    <!-- Main Content -->
    <div class="main-content">
        <!-- Page Header -->
        <div class="page-header">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <h1>@yield('page-title', 'Dashboard')</h1>
                </div>
                <div class="col-md-6">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb justify-content-md-end">
                            @yield('breadcrumb')
                        </ol>
                    </nav>
                </div>
            </div>
        </div>

        <!-- Alerts -->
        @include('layouts.partials.alerts')

        <!-- Page Content -->
        @yield('content')
    </div>
    
    <!-- Footer -->
    @include('layouts.footer')
    
    <!-- JavaScript Dependencies -->
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <!-- Custom Scripts -->
    @include('layouts.partials.scripts')
    
    @stack('scripts')
</body>
</html>