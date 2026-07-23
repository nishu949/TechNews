<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>@yield('title', 'TechNews - Developer Blog & Tech Articles')</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Developer Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700;800;900&family=JetBrains+Mono:wght@400;600;700&display=swap" rel="stylesheet">
    
    <!-- Font Awesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    
    <!-- Vite Assets -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Custom Styles -->
    <style>
        :root {
            --font-body: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            --font-code: 'JetBrains Mono', 'Fira Code', 'Cascadia Code', monospace;
            --font-size-fluid: clamp(1rem, 1vw + 0.8rem, 1.25rem);
        }
        
        * {
            box-sizing: border-box;
        }
        
        body {
            font-family: var(--font-body);
            font-size: var(--font-size-fluid);
            color: #1a202c;
            background-color: #f7fafc;
            line-height: 1.7;
            -webkit-font-smoothing: antialiased;
        }
        
        /* Typography */
        h1, h2, h3, h4, h5, h6 {
            font-weight: 800;
            line-height: 1.2;
            letter-spacing: -0.02em;
        }
        
        /* Code Styling */
        code, pre {
            font-family: var(--font-code);
        }
        
        pre {
            background: #1a202c;
            color: #f7fafc;
            padding: 1.5rem;
            border-radius: 8px;
            overflow-x: auto;
            margin: 1.5rem 0;
        }
        
        pre code {
            color: #e2e8f0;
            font-size: 0.9rem;
            line-height: 1.8;
        }
        
        .code-inline {
            background: #edf2f7;
            color: #2d3748;
            padding: 0.15rem 0.4rem;
            border-radius: 4px;
            font-size: 0.85em;
            font-family: var(--font-code);
        }
        
        /* Card Hover Effect */
        .hover-card {
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }
        
        .hover-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 30px rgba(0,0,0,0.1) !important;
        }
        
        .hover-bg:hover {
            background-color: #f7fafc;
        }
        
        /* Tag Pills */
        .tag-pill {
            display: inline-block;
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 600;
            transition: all 0.2s;
        }
        
        .tag-pill:hover {
            transform: translateY(-2px);
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        
        /* Scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }
        ::-webkit-scrollbar-track {
            background: #edf2f7;
        }
        ::-webkit-scrollbar-thumb {
            background: #a0aec0;
            border-radius: 4px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #718096;
        }
        
        /* Flash Messages */
        .alert {
            border-radius: 8px;
        }
    </style>
    
    @stack('styles')
</head>

<body>
    @include('components.navbar')

    <main class="container py-5">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        
        @yield('content')
    </main>

    @include('components.footer')
    
    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    @stack('scripts')
</body>
</html>