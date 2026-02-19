@extends('layouts.app')

@section('title', 'Cash Collection Services')
@section('meta_description', 'Reliable Cash Collection Services. Secure, timely, and professional cash handling for businesses.')

@section('content')

<!-- Hero Section -->
<section id="hero" class="hero section dark-background text-center py-5">
    <div class="container">
        <h1>Secure & Reliable Cash Collection</h1>
        <p>Professional cash collection services to keep your business finances safe and efficient.</p>
        <a href="#services" class="btn btn-primary mt-3">Our Services</a>
    </div>
</section>

<!-- About Section -->
<section id="about" class="about section py-5">
    <div class="container">
        <h2 class="text-center mb-4">Why Choose Our Cash Collection?</h2>
        <p class="text-center">We provide secure, timely, and professional cash collection services for businesses of all sizes. Our trained team ensures your cash is handled safely, with complete accountability and transparency.</p>
    </div>
</section>

<!-- Services Section -->
<section id="services" class="services section py-5 light-background">
    <div class="container">
        <h2 class="text-center mb-5">Our Services</h2>
        <div class="row g-4">

            <div class="col-md-4 text-center" data-aos="fade-up" data-aos-delay="100">
                <i class="bi bi-cash-stack display-4 mb-3 text-primary"></i>
                <h4>Cash Collection</h4>
                <p>Secure collection of cash from your business locations at scheduled times.</p>
            </div>

            <div class="col-md-4 text-center" data-aos="fade-up" data-aos-delay="200">
                <i class="bi bi-shield-check display-4 mb-3 text-success"></i>
                <h4>Safe Handling</h4>
                <p>All cash is handled by trained personnel using strict security protocols.</p>
            </div>

            <div class="col-md-4 text-center" data-aos="fade-up" data-aos-delay="300">
                <i class="bi bi-bar-chart-line display-4 mb-3 text-warning"></i>
                <h4>Real-Time Reporting</h4>
                <p>Get timely updates and reports for every cash collection transaction.</p>
            </div>

        </div>
    </div>
</section>

@endsection