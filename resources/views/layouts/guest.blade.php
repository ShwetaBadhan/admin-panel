<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Admin Panel - Dashboard</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <!-- Favicon icon -->
    <link rel="shortcut icon" href="{{ asset('admin/images/logo/d.png') }}" type="image/x-icon">
    <link href="{{ asset('vendor/bootstrap-select/dist/css/bootstrap-select.min.css') }}" rel="stylesheet">
    <link class="main-css" href="{{ asset('css/style.css') }}" rel="stylesheet">

    <script src="https://challenges.cloudflare.com/turnstile/v0/api.js" async defer></script>


    <style>
       .auth-image {
            background: url('{{ asset('images/logo/auth3.jpg') }}') no-repeat center center;
            background-size: cover;
            min-height: 100vh;
        }
        body{
            background: #d7e9ff;
        }
    </style>
</head>

<body class="font-sans text-gray-900 antialiased">

    <div class="container-fluid p-0 auth-page">
        <div class="row g-0">
            <!-- Left Side Image -->
            <div class="col-md-7 auth-image d-none d-md-block"></div>

            <!-- Right Side Form -->
            <div class="col-md-5 bg-white auth-form-section">
                <div class="auth-form  ">
                    <div class="container">
                        <div class="text-center mb-3">
                            <a href="">
                                <img src="{{ asset('images/logo/hozlogo.png') }}" alt="">
                            </a>
                        </div>
                        <h4 class="text-center mb-4">{{ $title ?? 'Sign in your account' }}</h4>

                        <!-- Form Slot -->
                        {{ $slot }}

                    </div>




                </div>
            </div>
        </div>
    </div>



</body>

</html>