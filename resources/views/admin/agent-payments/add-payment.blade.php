@extends('admin.layouts.admin')
@section('title', 'Receive Cash Collection')

@section('content')

<div class="pagetitle">
    <h1>Receive Cash Collection</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('receive-payment.index') }}">Cash Collections</a></li>
            <li class="breadcrumb-item active">New Collection</li>
        </ol>
    </nav>
</div>

<section class="section">
    <div class="row mb-3">
        <div class="col-lg-12">
            <a class="btn btn-secondary" href="{{ route('receive-payment.index') }}"> ← Back </a>
        </div>
    </div>

    <div class="row">
        <form id="CashCollectionForm" method="POST" action="{{ route('receive-payment.save') }}">
            @csrf

            <div class="col-lg-8">

                <!-- Agent -->
                <div class="card mb-3">
                    <div class="card-body">
                        <div class="row align-items-center mb-3 mt-3">
                            <label for="agent_id" class="col-sm-3 col-form-label">Agent <span class="text-danger">*</span></label>
                            <div class="col-sm-9">
                                <select class="form-control" name="agent_id" id="agent_id">
                                    <option value="">Select Agent</option>
                                    @foreach ($agents as $agent)
                                        <option value="{{ $agent->id }}">{{ $agent->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Account -->
                <div class="card mb-3">
                    <div class="card-body">
                        <div class="row align-items-center mb-3 mt-3">
                            <label for="account_id" class="col-sm-3 col-form-label">Account <span class="text-danger">*</span></label>
                            <div class="col-sm-9">
                                <select class="form-control" name="account_id" id="account_id">
                                    <option value="">Select Account</option>
                                    @foreach ($accounts as $account)
                                        <option value="{{ $account->id }}">{{ $account->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Collection Date -->
                <div class="card mb-3">
                    <div class="card-body">
                        <div class="row align-items-center mb-3">
                            <label for="collection_date" class="col-sm-3 col-form-label">Collection Date <span class="text-danger">*</span></label>
                            <div class="col-sm-9">
                                <input type="date" class="form-control" name="collection_date" id="collection_date" value="{{ date('Y-m-d') }}">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Amount -->
                <div class="card mb-3">
                    <div class="card-body">
                        <div class="row align-items-center mb-3">
                            <label for="amount" class="col-sm-3 col-form-label">Amount <span class="text-danger">*</span></label>
                            <div class="col-sm-9">
                                <input type="number" class="form-control" name="amount" id="amount" step="0.01" min="0" placeholder="Enter amount">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Notes -->
                <div class="card mb-3">
                    <div class="card-body">
                        <div class="row align-items-center mb-3">
                            <label for="notes" class="col-sm-3 col-form-label">Notes</label>
                            <div class="col-sm-9">
                                <textarea class="form-control" name="notes" id="notes" placeholder="Optional notes"></textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="text-end">
                    <button type="submit" class="btn btn-primary btn-lg">Record Collection</button>
                </div>

            </div>
        </form>
    </div>
</section>

@endsection



@section('javascript')
  @include('admin.agent-payments.add-payment-js')
@endsection
