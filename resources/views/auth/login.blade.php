{{-- resources/views/auth/login.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>BOM Management System - Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            min-height: 100vh;
               background:
        url('https://images.unsplash.com/photo-1565793298595-6a879b1d9492?q=80&w=1600&auto=format&fit=crop');

            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        
        .login-container {
            width: 100%;
            max-width: 1200px;
            background: white;
            border-radius: 30px;
            overflow: hidden;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
            display: flex;
            flex-wrap: wrap;
        }
        
        /* Left Side - Image Section */
        .login-image {
            flex: 1;
            min-width: 300px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 40px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            position: relative;
            overflow: hidden;
        }
        
        .login-image::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="white" opacity="0.1"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15h-2v-2h2v2zm0-4h-2V7h2v6z"/></svg>') repeat;
            opacity: 0.1;
        }
        
        .image-content {
            position: relative;
            z-index: 1;
            text-align: center;
            color: white;
        }
        
        .image-icon {
            font-size: 80px;
            margin-bottom: 30px;
            animation: float 3s ease-in-out infinite;
        }
        
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
        }
        
        .image-content h2 {
            font-size: 32px;
            font-weight: 700;
            margin-bottom: 20px;
        }
        
        .image-content p {
            font-size: 16px;
            opacity: 0.9;
            line-height: 1.6;
            margin-bottom: 40px;
        }
        
        .features-list {
            text-align: left;
            margin-top: 30px;
        }
        
        .features-list li {
            list-style: none;
            margin-bottom: 15px;
            font-size: 14px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .features-list li i {
            width: 24px;
            font-size: 18px;
        }
        
        /* Right Side - Form Section */
        .login-form {
            flex: 1;
            min-width: 350px;
            padding: 50px;
            background: white;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
        
        .form-header {
            text-align: center;
            margin-bottom: 40px;
        }
        
        .form-header h3 {
            font-size: 28px;
            font-weight: 700;
            color: #333;
            margin-bottom: 10px;
        }
        
        .form-header p {
            color: #666;
            font-size: 14px;
        }
        
        .form-group {
            margin-bottom: 25px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: #333;
            font-size: 14px;
        }
        
        .form-group label i {
            margin-right: 8px;
            color: #667eea;
        }
        
        .input-group {
            position: relative;
        }
        
        .form-control {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #e1e5e9;
            border-radius: 12px;
            font-size: 14px;
            transition: all 0.3s ease;
        }
        
        .form-control:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }
        
        .form-control.is-invalid {
            border-color: #dc3545;
        }
        
        .invalid-feedback {
            color: #dc3545;
            font-size: 12px;
            margin-top: 5px;
        }
        
        .checkbox-group {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
        }
        
        .checkbox-label {
            display: flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
            font-size: 14px;
            color: #666;
        }
        
        .checkbox-label input {
            cursor: pointer;
        }
        
        .forgot-link {
            color: #667eea;
            text-decoration: none;
            font-size: 14px;
            transition: color 0.3s ease;
        }
        
        .forgot-link:hover {
            color: #764ba2;
            text-decoration: underline;
        }
        
        .btn-login {
            width: 100%;
            padding: 14px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            border-radius: 12px;
            color: white;
            font-weight: 600;
            font-size: 16px;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }
        
        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(102, 126, 234, 0.3);
        }
        
        .register-link {
            text-align: center;
            margin-top: 25px;
            padding-top: 25px;
            border-top: 1px solid #e1e5e9;
            font-size: 14px;
            color: #666;
        }
        
        .register-link a {
            color: #667eea;
            text-decoration: none;
            font-weight: 500;
        }
        
        .register-link a:hover {
            text-decoration: underline;
        }
        
        .alert {
            padding: 12px 15px;
            border-radius: 12px;
            margin-bottom: 20px;
            font-size: 14px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .alert-danger {
            background: #fef2f2;
            border: 1px solid #fecaca;
            color: #dc2626;
        }
        
        .alert-success {
            background: #f0fdf4;
            border: 1px solid #bbf7d0;
            color: #16a34a;
        }
        
        /* Responsive Design */
        @media (max-width: 768px) {
            .login-image {
                display: none;
            }
            
            .login-form {
                min-width: 100%;
                padding: 30px;
            }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <!-- Left Side - Image Section -->
        <div class="login-image">
            <div class="image-content">
                <div class="image-icon">
                    <i class="fas fa-industry"></i>
                </div>
                <h2>Welcome Back!</h2>
                <p>Access your BOM Management Dashboard to manage manufacturing workflows efficiently.</p>
                
                <ul class="features-list">
                    <li>
                        <i class="fas fa-check-circle"></i>
                        <span>BOM Upload & Management</span>
                    </li>
                    <li>
                        <i class="fas fa-check-circle"></i>
                        <span>Real-time Inventory Check</span>
                    </li>
                    <li>
                        <i class="fas fa-check-circle"></i>
                        <span>Auto Purchase Intent Generation</span>
                    </li>
                    <li>
                        <i class="fas fa-check-circle"></i>
                        <span>Material Allocation Tracking</span>
                    </li>
                    <li>
                        <i class="fas fa-check-circle"></i>
                        <span>Role-based Access Control</span>
                    </li>
                </ul>
            </div>
        </div>
        
        <!-- Right Side - Form Section -->
        <div class="login-form">
            <div class="form-header">
                <h3>Sign In</h3>
                <p>Enter your credentials to access your account</p>
            </div>
            
            @if(session('error'))
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-circle"></i>
                    {{ session('error') }}
                </div>
            @endif
            
            @if($errors->any())
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-circle"></i>
                    {{ $errors->first() }}
                </div>
            @endif
            
            @if(session('success'))
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i>
                    {{ session('success') }}
                </div>
            @endif
            
            <form method="POST" action="{{ route('login') }}">
                @csrf
                
                <div class="form-group">
                    <label for="email">
                        <i class="fas fa-envelope"></i> Email Address
                    </label>
                    <div class="input-group">
                        <input type="email" class="form-control @error('email') is-invalid @enderror" 
                               id="email" name="email" value="{{ old('email') }}" 
                               placeholder="Enter your email" required autofocus>
                    </div>
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="form-group">
                    <label for="password">
                        <i class="fas fa-lock"></i> Password
                    </label>
                    <div class="input-group">
                        <input type="password" class="form-control @error('password') is-invalid @enderror" 
                               id="password" name="password" 
                               placeholder="Enter your password" required>
                    </div>
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="checkbox-group">
                    <label class="checkbox-label">
                        <input type="checkbox" name="remember" id="remember">
                        <span>Remember Me</span>
                    </label>
                </div>
                
                <button type="submit" class="btn-login">
                    <i class="fas fa-sign-in-alt"></i> Sign In
                </button>
            </form>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>