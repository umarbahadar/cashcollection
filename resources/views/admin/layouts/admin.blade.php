<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <title>@yield('title', config('app.name'))</title>
    <link rel="icon" type="image/png" href="{{ asset('front-theme/assets/img/favicon.png') }}">
    <meta content="" name="description">
    <meta content="" name="keywords">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Favicons -->
    <!-- <link href="{{ asset('img/favicon.png') }}" rel="icon"> -->
    <!-- <link rel="icon" href="{{ asset('storage/images/img/icon.png') }}" type="icon"> -->
    <!-- <link href="{{ asset('img/apple-touch-icon.png') }}" rel="apple-touch-icon"> -->

    <!-- Google Fonts -->
    <link href="https://fonts.gstatic.com" rel="preconnect">
    <link
        href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i"
        rel="stylesheet">

    <!-- Vendor CSS Files -->
    <link href="{{ asset('back-theme/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('back-theme/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet">
    <link href="{{ asset('back-theme/boxicons/css/boxicons.min.css') }}" rel="stylesheet">
    <link href="{{ asset('back-theme/quill/quill.snow.css') }}" rel="stylesheet">
    <link href="{{ asset('back-theme/quill/quill.bubble.css') }}" rel="stylesheet">
    <link href="{{ asset('back-theme/remixicon/remixicon.css') }}" rel="stylesheet">
    <link href="{{ asset('back-theme/simple-datatables/style.css') }}" rel="stylesheet">
    <link href="{{ asset('back-theme/css/style.css') }}" rel="stylesheet">


    <!-- DataTable CSS Files -->
    <link href="{{ asset('libraries/datatables/jquery.dataTables.min.css') }}" rel="stylesheet"/>
    <link href="{{ asset('libraries/datatables/buttons.dataTables.min.css') }}" rel="stylesheet"/>

    <!-- Libs -->
    <link href="{{ asset('libraries/toastr/toastr.min.css') }}" rel="stylesheet">

    <!-- Template Main CSS File -->
    <link href="{{ asset('back-theme/css/style.css') }}" rel="stylesheet">

    <!-- New Style -->
    <!-- <link href="{{ asset('back-theme/boxicons/css/style.css') }}" rel="stylesheet"> -->
     <style>
        /* Main Table Style */
        .main_table{
        border-collapse: collapse ;
        }


        .main_table thead tr th{
        color: var(--tableheadcolor)!important;
        white-space: normal;
        font-size:14px;
        font-weight: 500;
        padding: 20px;
        line-height: 1.5rem;
        background-color: #f2f2f2;
        }
        .main_table tbody tr:nth-child(even){
        background-color: #f2f2f2!important;
        }
        .main_table tbody tr:nth-child(odd) {
        background-color: #fff!important;
        }
        .main_table tbody tr td{
        white-space: nowrap;
        padding: 20px;
        line-height: 1.5rem;
        font-size: 13px;
        /* font-weight: 500; */
        }
     </style>

    <!-- =======================================================
  * Template Name: NiceAdmin
  * Updated: Sep 18 2023 with Bootstrap v5.3.2
  * Template URL: https://bootstrapmade.com/nice-admin-bootstrap-admin-html-template/
  * Author: BootstrapMade.com
  * License: https://bootstrapmade.com/license/
  ======================================================== -->

    <!-- DataTable CSS Files -->
    <link href="{{ asset('libraries/datatables/jquery.dataTables.min.css') }}" rel="stylesheet"/>
    <link href="{{ asset('libraries/datatables/buttons.dataTables.min.css') }}" rel="stylesheet"/>
    
    <!-- Select 2 -->
    <link href="{{ asset('libraries/select2/select2.min.css') }}" rel="stylesheet"/>


     <!-- jQuery -->
     <script src="{{ asset('libraries/jquery/jquery-3.6.1.min.js') }}"></script>
</head>

<body>
    <!-- Loader  -->
    <div id="loading">
        <img id="loading-image" src="{{ asset('img/loader.gif') }}" alt="Loading..." />
   </div>

    <!-- ======= Header ======= -->
    @include('admin.layouts.header')
    <!-- End Header -->

    <!-- ======= Sidebar ======= -->
    @include('admin.layouts.sidebar')

    <!-- End Sidebar-->

    <main id="main" class="main">
        

        @yield('content')

    </main><!-- End #main -->

    <!-- ======= Footer ======= -->
    @include('admin.layouts.footer')
    @yield('javascript')

   <!-- End Footer -->

    <!-- <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i
            class="bi bi-arrow-up-short"></i></a> -->

    <!-- DataTable JS Files -->
    <script src="{{ asset('libraries/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('libraries/datatables/dataTables.buttons.min.js') }}"></script>
            
    <!-- Vendor JS Files -->
    <script src="{{ asset('back-theme/apexcharts/apexcharts.min.js') }}"></script>
    <script src="{{ asset('back-theme/bootstrap/js/bootstrap.bundle.min.js')  }}"></script>
    <script src="{{ asset('back-theme/chart.js/chart.umd.js')  }}"></script>
    <script src="{{ asset('back-theme/echarts/echarts.min.js')  }}"></script>
    <script src="{{ asset('back-theme/quill/quill.min.js')  }}"></script>
    <script src="{{ asset('back-theme/simple-datatables/simple-datatables.js')  }}"></script>
    <script src="{{ asset('back-theme/tinymce/tinymce.min.js')  }}"></script>
    <script src="{{ asset('back-theme/php-email-form/validate.js')  }}"></script>


    <!-- Template Main JS File -->
    <script src="{{ asset('back-theme/js/main.js') }}"></script>

     <!-- Select 2 -->
     <script src="{{ asset('libraries/select2/select2.min.js') }}"> </script>

    <!-- Libs -->
    <script src="{{ asset('libraries/toastr/toastr.min.js') }}" defer></script>
    <script src="{{ asset('libraries/swal/swal.js') }}"></script>


    <!-- CK Editor -->
    <script src="https://cdn.ckeditor.com/4.16.0/standard/ckeditor.js"></script>
    




</body>
</html>