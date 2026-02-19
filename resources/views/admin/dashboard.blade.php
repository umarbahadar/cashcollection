@extends('admin.layouts.admin')
@section('content')
<div class="pagetitle">
    <h1>Dashboard</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="index.html">Home</a></li>
            <li class="breadcrumb-item active">Dashboard</li>
        </ol>
    </nav>
</div><!-- End Page Title -->

<section class="section dashboard">

    <div class="row">

        <!-- Left side columns -->
        <div class="col-lg-12">
        <div class="row">
                 <!-- Sales Card -->
                 <div class="col-xxl-4 col-md-6">
                    <div class="card info-card sales-card">

                    </div>
                </div><!-- End Sales Card -->
                   <!-- Sales Card -->
                <div class="col-xxl-4 col-md-6">
                    <div class="card info-card sales-card">
                    </div>
                </div>   
</div>
            
    </div>
</section>
@endsection