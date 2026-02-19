<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="description" content="NextIn Technologies builds scalable web applications, ERP systems, and SEO-optimized eCommerce solutions. Trusted by businesses for end-to-end digital transformation since 201X.">

        <title>@yield('title', 'NextIn Technologies | Technology Solutions for Growth')</title>
        <link rel="icon" type="image/png" href="{{ asset('front-theme/assets/img/favicon.png') }}">


        <!-- Theme Resources -->
        <!-- <link href="https://fonts.googleapis.com" rel="preconnect"> -->
        <!-- <link href="https://fonts.gstatic.com" rel="preconnect" crossorigin> -->
        <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&family=Raleway:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Nunito:ital,wght@0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">

        <!-- Vendor CSS Files -->
        <link href="{{ asset('front-theme/assets/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
        <link href="{{ asset('front-theme/assets/vendor/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet">
        <link href="{{ asset('front-theme/assets/vendor/aos/aos.css') }}" rel="stylesheet">
        <link href="{{ asset('front-theme/assets/vendor/glightbox/css/glightbox.min.css') }}" rel="stylesheet">
        <link href="{{ asset('front-theme/assets/vendor/swiper/swiper-bundle.min.css') }}" rel="stylesheet">

        <!-- Main CSS File -->
        <link href="{{ asset('front-theme/assets/css/main.css') }}" rel="stylesheet">

        <!-- Libs -->
        <link href="{{ asset('libraries/toastr/toastr.min.css') }}" rel="stylesheet">
         <script src="{{ asset('libraries/jquery/jquery-3.6.1.min.js') }}"></script>

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased index-page">
        
        <div class="min-h-screen bg-gray-100">
       
            @include('layouts.header')

          
            <main class="main">
                 @yield('content')
            </main>

            @include('layouts.footer')

            <!-- Scroll Top -->
            <a href="#" id="scroll-top" class="scroll-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

            <!-- Preloader -->
            <div id="preloader"></div>
        </div>
        @yield('javascript')

        <!-- Vendor JS Files -->
        <script src="{{ asset('front-theme/assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
        <script src="{{ asset('front-theme/assets/vendor/php-email-form/validate.js') }}"></script>
        <script src="{{ asset('front-theme/assets/vendor/aos/aos.js') }}"></script>
        <script src="{{ asset('front-theme/assets/vendor/glightbox/js/glightbox.min.js') }}"></script>
        <script src="{{ asset('front-theme/assets/vendor/imagesloaded/imagesloaded.pkgd.min.js') }}"></script>
        <script src="{{ asset('front-theme/assets/vendor/isotope-layout/isotope.pkgd.min.js') }}"></script>
        <script src="{{ asset('front-theme/assets/vendor/waypoints/noframework.waypoints.js') }}"></script>
        <script src="{{ asset('front-theme/assets/vendor/swiper/swiper-bundle.min.js') }}"></script>

        <!-- Main JS File -->
        <script src="{{ asset('front-theme/assets/js/main.js') }}"></script>

        
        <!-- Libs -->
        <script src="{{ asset('libraries/toastr/toastr.min.js') }}" defer></script>
        <script src="{{ asset('libraries/swal/swal.js') }}"></script>

       
    </body>
</html>
