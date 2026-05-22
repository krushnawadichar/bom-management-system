<style>
    :root {
        --sidebar-width: 280px;
        --header-height: 60px;
        --primary-color: #2c3e50;
        --secondary-color: #34495e;
        --success-color: #27ae60;
        --danger-color: #e74c3c;
        --warning-color: #f39c12;
        --info-color: #3498db;
    }

    body {
        background-color: #f4f6f9;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        overflow-x: hidden;
    }

    /* Top Header Styles */
    .top-header {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        height: var(--header-height);
        background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
        color: white;
        z-index: 1030;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }

    .top-header .navbar-brand {
        font-size: 1.5rem;
        font-weight: bold;
        padding-left: 20px;
    }

    .top-header .navbar-brand i {
        margin-right: 10px;
    }

    .top-header .btn-link {
        text-decoration: none;
    }

    /* Sidebar Styles */
    .sidebar {
        position: fixed;
        top: var(--header-height);
        left: 0;
        bottom: 0;
        width: var(--sidebar-width);
        background: white;
        border-right: 1px solid #e0e0e0;
        overflow-y: auto;
        z-index: 1020;
        transition: all 0.3s ease;
        box-shadow: 2px 0 5px rgba(0,0,0,0.05);
    }

    .sidebar::-webkit-scrollbar {
        width: 5px;
    }

    .sidebar::-webkit-scrollbar-track {
        background: #f1f1f1;
    }

    .sidebar::-webkit-scrollbar-thumb {
        background: #888;
        border-radius: 5px;
    }

    .sidebar::-webkit-scrollbar-thumb:hover {
        background: #555;
    }

    .sidebar .nav-section {
        padding: 20px 0;
    }

    .sidebar .nav-section-title {
        padding: 10px 25px;
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 1px;
        color: #7f8c8d;
        font-weight: 600;
        margin-top: 10px;
    }

    .sidebar .nav-item {
        margin: 5px 0;
    }

    .sidebar .nav-link {
        padding: 12px 25px;
        color: #555;
        transition: all 0.3s ease;
        border-radius: 0;
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .sidebar .nav-link i {
        width: 20px;
        font-size: 1.1rem;
    }

    .sidebar .nav-link:hover {
        background: #f0f2f5;
        color: var(--primary-color);
        padding-left: 30px;
    }

    .sidebar .nav-link.active {
        background: linear-gradient(90deg, #e8f4f8 0%, transparent 100%);
        color: var(--info-color);
        border-right: 3px solid var(--info-color);
        font-weight: 500;
    }

    .sidebar .nav-link .badge {
        margin-left: auto;
    }

    /* Main Content Area */
    .main-content {
        margin-left: var(--sidebar-width);
        margin-top: var(--header-height);
        min-height: calc(100vh - var(--header-height) - 60px);
        padding: 20px;
        transition: all 0.3s ease;
    }

    /* Footer Styles */
    .footer {
        margin-left: var(--sidebar-width);
        background: white;
        border-top: 1px solid #e0e0e0;
        padding: 15px 20px;
        font-size: 0.875rem;
        color: #7f8c8d;
    }

    /* Page Header */
    .page-header {
        background: white;
        padding: 20px;
        border-radius: 10px;
        margin-bottom: 20px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
    }

    .page-header h1 {
        font-size: 1.75rem;
        margin: 0;
        color: var(--primary-color);
    }

    .page-header .breadcrumb {
        margin: 0;
        background: transparent;
        padding: 0;
    }

    /* Cards */
    .card {
        border: none;
        border-radius: 10px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        margin-bottom: 20px;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .card:hover {
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }

    .card-header {
        background: white;
        border-bottom: 1px solid #e0e0e0;
        padding: 15px 20px;
        font-weight: 600;
        border-radius: 10px 10px 0 0 !important;
    }

    /* Stats Cards */
    .stat-card {
        background: white;
        border-radius: 10px;
        padding: 20px;
        margin-bottom: 20px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        transition: transform 0.3s ease;
        cursor: pointer;
    }

    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }

    .stat-card .stat-icon {
        font-size: 2.5rem;
        opacity: 0.7;
    }

    .stat-card .stat-value {
        font-size: 2rem;
        font-weight: bold;
        margin: 10px 0;
    }

    .stat-card .stat-label {
        color: #7f8c8d;
        font-size: 0.875rem;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    /* Table Styles */
    .table {
        margin-bottom: 0;
    }

    .table thead th {
        background-color: #f8f9fa;
        border-bottom: 2px solid #dee2e6;
        font-weight: 600;
    }

    /* Button Styles */
    .btn-primary {
        background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
        border: none;
    }

    .btn-primary:hover {
        background: linear-gradient(135deg, var(--secondary-color) 0%, var(--primary-color) 100%);
        transform: translateY(-1px);
    }

    /* Responsive */
    @media (max-width: 768px) {
        .sidebar {
            left: -280px;
        }
        
        .sidebar.show {
            left: 0;
        }
        
        .main-content, .footer {
            margin-left: 0;
        }
        
        .top-header .menu-toggle {
            display: block;
        }
        
        .stat-card .stat-value {
            font-size: 1.5rem;
        }
    }

    /* Loading Spinner */
    .loading-overlay {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(255,255,255,0.8);
        z-index: 9999;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    /* Animation */
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .fade-in {
        animation: fadeIn 0.5s ease;
    }
</style>