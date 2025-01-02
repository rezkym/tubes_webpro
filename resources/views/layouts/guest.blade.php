<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name') }} - @yield('title')</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Custom CSS -->
    <style>
        body {
            background-color: #f8f9fa;
        }
        .auth-card {
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            border: none;
        }
        .auth-header {
            background-color: #f8f9fa;
            border-bottom: 1px solid #eee;
            border-radius: 10px 10px 0 0;
            padding: 20px;
        }
        .auth-body {
            padding: 30px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center align-items-center min-vh-100">
            <div class="col-md-5">
                <div class="text-center mb-4">
                    <h2>{{ config('app.name') }}</h2>
                </div>
                
                <div class="card auth-card">
                    <div class="auth-header">
                        <h4 class="mb-0">@yield('header')</h4>
                    </div>
                    
                    <div class="auth-body">
                        @yield('content')
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>