@extends('admin.layouts.admin')
@section('content')

<div class="pagetitle">
    <h1>Project Details</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('projects.index') }}">Projects</a></li>
            <li class="breadcrumb-item active"><a class="active" href="">Project Detail</a></li>

        </ol>
    </nav>
</div><!-- End Page Title -->

<section class="section profile">
        <div class="row row-on-table-top btns-right">
            <div class="col-lg-12">
                <a type="button" class="btn btn-primary" href="{{ route('projects.index') }}">< Back</a>
            </div>
        </div>
       
        
        <div class="row mb-1 mt-3">
            <div class="col-lg-6">
                <span class="card-title" style="font-size: 1em;">Crated at: 
                    <span style="color: #012970"> {{ $projects->created_at }}</span>
                </span>
            </div>
            
        </div>

        <div class="row">
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-body pt-3">

                        <h5 class="card-title">General Details</h5>

                        <div class="row mb-3">
                            <div class="col-lg-3 col-md-4 label "><label for="">Project Image:</label></div>
                            <div class="col-lg-9 col-md-8"><img src="{{ asset('storage/images/projects/' . $projects->image) }}" alt="Project Image" class="table_image">
                            </div>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-lg-3 col-md-4 label "><label for="">Project Name:</label></div>
                            <div class="col-lg-9 col-md-8"> {{ $projects->name}} </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-lg-3 col-md-4 label "><label for="">Category:</label></div>
                            <div class="col-lg-9 col-md-8"> {{ $projects->category->name ?? '--'}} </div>
                        </div>


                        <div class="row mb-3">
                            <div class="col-lg-3 col-md-4 label "><label for="">Description:</label></div>
                            <div class="col-lg-9 col-md-8"> {{ $projects->description}} </div>
                        </div>
                       
                       

                        <div class="row mb-3">
                            <div class="col-lg-3 col-md-4 label"><label for="">Link</label></div>
                            <div class="col-lg-9 col-md-8">
                                {{ $projects->link }}
                            </div>
                        </div>

                    </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

<!-- Add Role Modal -->

@endsection

@section('javascript')
  @include('admin.projects.view-project-js')                   
@endsection

