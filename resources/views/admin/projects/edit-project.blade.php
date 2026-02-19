@extends('admin.layouts.admin')
@section('content')

<div class="pagetitle">
    <h1>Edit Project</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('projects.index') }}">Projects</a></li>
            <li class="breadcrumb-item active"><a class="active" href="">Edit Project</a></li>

        </ol>
    </nav>
</div><!-- End Page Title -->

<section class="section dashboard">

    <div class="row row-on-table-top btns-right">
        <div class="col-lg-12">
            <a type="button" class="btn btn-primary" href="{{ route('projects.index') }}">< Back</a>
        </div>
    </div>
    <br>
    <div class="row top_padded_row">
      <div class="col-lg-12">
        <div class="card">
          <div class="card-body table-responsive">
            <br>
            <form id="itemForm" enctype="multipart/form-data">
            @csrf
            <div class="row">
                <div class="col-lg-12">
                    <h5 class="card-title">Update Project Information:</h5>
                    <div class="row mb-3">
                        <label for="inputText" class="col-sm-2 col-form-label">Name</label>
                        <div class="col-sm-7">
                            <input type="hidden" name="id" id="id" value="{{ $projects->id }}">
                            <input type="text" name="name" id="name" class="form-control" value="{{ $projects->name }}">
                        </div>
                    </div>


                    <div class="row mb-3">
                        <label for="long_description" class="col-sm-2 col-form-label">Category</label>
                        <div class="col-sm-7">
                            <select name="category_id" class="form-control custom_select">
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" 
                                            {{ $category->id == $projects->category_id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="long_description" class="col-sm-2 col-form-label">Description</label>
                        <div class="col-sm-7">
                            <textarea class="form-control" style="height: 100px" name="description" id="description">{{ $projects->description }}</textarea>
                        </div>
                    </div>
                    
                    <div class="row mb-3" id="price_row">
                        <label for="price" class="col-sm-2 col-form-label">Project Link</label>
                        <div class="col-sm-7">
                            <input type="text" class="form-control" name="link" id="link" value="{{ $projects->link }}">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label">Image</label>
                        <div class="col-md-7">
                            <label class="image-upload-container">
                            <input type="file" name="image" id="imageInput">
                            <div id="imagePreview">
                                @if($projects->image)
                                    <img src="{{ asset('storage/images/projects/' . $projects->image) }}" alt="Project Image" width="100">
                                @endif
                            </div>
                                
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row text-right btns-right">
                <div class="col-lg-12 text-right">
                    <button type="submit" name="submit" class="btn btn-primary">Update</button>
                </div>
            </div>
          
            </form>
            
          </div>
        </div>
    </div>
</section>



@endsection
<!-- JS Included -->
@section('javascript')
  @include('admin.projects.edit-project-js')                   
@endsection