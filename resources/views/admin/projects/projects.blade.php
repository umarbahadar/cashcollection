@extends('admin.layouts.admin')
@section('content')

<div class="pagetitle">
    <h1>Projects</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
            <li class="breadcrumb-item active"><a class="active" href="{{ route('projects.index') }}">Projects</a></li>
        </ol>
    </nav>
</div><!-- End Page Title -->

<section class="section dashboard">
  <form id="filter_form">
      <div class="row row-on-table-top filter-row">
          <!-- <div class="col-lg-3">
              <div class="row mb-3">
                  <label for="from_date" class="col-sm-2 col-form-label">From:</label>
                  <div class="col-sm-7">
                      <input type="date" class="form-control form-control-filter" id="from_date" name="from_date">
                  </div>
              </div>
          </div>
          <div class="col-lg-3">
              <div class="row mb-3">
                  <label for="to_date" class="col-sm-2 col-form-label">To:</label>
                  <div class="col-sm-7">
                      <input type="date" class="form-control" id="to_date" name="to_date">
                  </div>
              </div>
          </div> -->

        <div class="col-lg-3">
              <div class="row mb-3">
                  <label for="status_id" class="col-sm-2 col-form-label">Category:</label>
                  <div class="col-sm-7">
                            <select class="form-select form-control custom_select" aria-label="Select name" name="category_id" id="category_id">
                                <option value="" selected>Select Name</option>
                                @foreach ($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                  </div>
              </div>
        </div>

         
        <div class="col-lg-3">
              <div class="row mb-3">
                  <label for="status_id" class="col-sm-2 col-form-label">Proejct Name:</label>
                  <div class="col-sm-7">
                            <select class="form-select form-control custom_select" aria-label="Select name" name="id" id="id">
                                <option value="" selected>Select Project</option>
                                @foreach ($projects as $project)
                                <option value="{{ $project->id }}">{{ $project->name }}</option>
                                @endforeach
                            </select>
                  </div>
              </div>
          </div>
          

          
         
          <div class="col-lg-3">
              <button type="submit" class="btn btn-sm btn-success">Apply</button>
              <a class="btn btn-sm btn-secondary" href="">Reset</a>
          </div>
      </div>
    </form>
    <br>


    <div class="row row-on-table-top btns-right">
        <div class="col-lg-12">
            <a type="button" class="btn btn-primary" href="{{ route('projects.add') }}"><i class="bi bi-plus"></i>&nbsp;Add New Project</a>
        </div>
    </div>
    <br>
    <div class="row top_padded_row">
      <div class="col-lg-12">
        <div class="card">
          <div class="card-body table-responsive">
            <br>
            <table id="items_table" class="table main_table">
              <thead>
                <tr>
                  <th scope="col">Sr.#</th>
                  <th scope="col">Image</th>
                  <th scope="col">Project</th>
                  <th scope="col">Category</th>
                  <th scope="col">Action</th>
                </tr>
              </thead>
            </table>
          </div>
        </div>
    </div>
</section>
@endsection

<!-- JS Included -->
@section('javascript')
  @include('admin.projects.projects-js')                   
@endsection



