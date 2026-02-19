@extends('admin.layouts.admin')
@section('content')
@section('title', 'Agent Payments')

<div class="pagetitle">
    <h1>Agent Payments</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
            <li class="breadcrumb-item active"><a class="active" href="{{ route('receive-payment.index') }}">Client Payments</a></li>
        </ol>
    </nav>
</div><!-- End Page Title -->

<section class="section">
    <!-- Filter Row -->
    <form id="filter_form" class="mb-3">
        <div class="row align-items-end">

            <!-- Agent -->
            <div class="col-md-3 col-sm-6 mb-2">
                <label for="client_id" class="form-label">Agents</label>
                <select class="form-control" name="client_id" id="client_id">
                    <option value="">Select Agent</option>
                    @foreach ($agents as $agent)
                        <option value="{{ $agent->id }}">{{ $agent->name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Date From -->
            <div class="col-md-2 col-sm-6 mb-2">
                <label for="date_from" class="form-label">From</label>
                <input type="date" name="date_from" id="date_from" class="form-control">
            </div>

            <!-- Date To -->
            <div class="col-md-2 col-sm-6 mb-2">
                <label for="date_to" class="form-label">To</label>
                <input type="date" name="date_to" id="date_to" class="form-control">
            </div>

            <!-- Buttons -->
            <div class="col-md-3 col-sm-6 mb-2">
                <div class="btn-group-responsive">
                    <button type="submit" class="btn btn-success btn-sm">Apply</button>
                    <a href="{{ route('receive-payment.index') }}" class="btn btn-secondary btn-sm">Reset</a>
                </div>
            </div>

        </div>
    </form>



    <br>

    <!-- Add New Payment Method -->
    <div class="row row-on-table-top btns-right">
        <div class="col-lg-12">
            <a class="btn btn-primary" href="{{ route('receive-payment.add') }}">
                <i class="bi bi-plus"></i>&nbsp;Add New Payment
            </a>
        </div>
    </div>
    <br>

    <!-- Payment Methods List -->
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Accounts List</h5>
                        <!-- Table with stripped rows -->
                   <table id="collections_table" class="table main_table table-responsive">
                        <thead>
                            <tr>
                                <th scope="col">Sr.#</th>
                                <th scope="col">Agent</th>
                                <th scope="col">Account</th>
                                <th scope="col">Type</th>
                                <th scope="col">Date</th>
                                <th scope="col">Amount</th>
                                <th scope="col">Action</th>
                            </tr>
                        </thead>
                    </table>


                    <!-- End Table with stripped rows -->
                </div>
            </div>
        </div>
    </div>
</section>

@endsection

<!-- JS Included -->
@section('javascript')
  @include('admin.agent-payments.payments-js')                   
@endsection
