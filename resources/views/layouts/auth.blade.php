<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>@yield('title','TechNews')</title>

    @vite(['resources/css/app.css','resources/js/app.js'])
</head>

<body class="bg-light">

@include('components.navbar')

<div class="container py-5">

    <div class="row justify-content-center">

        <div class="col-md-6">

            <div class="card shadow">

                <div class="card-body p-4">

                    @yield('content')

                </div>

            </div>

        </div>

    </div>

</div>

@include('components.footer')

</body>
</html>