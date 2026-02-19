@extends('customer.layouts.customer')
@section('title', 'Edit Payment')
@section('content')

<style>
    /* Reuse supplier flow styles */
    .account-card {
        display: block;
        border: 1px solid #e1e4e8;
        border-radius: 10px;
        padding: 14px;
        margin-bottom: 12px;
        cursor: pointer;
        transition: all 0.2s ease;
        background: #fff;
    }

    .account-card input[type="radio"] { display: none; }

    .account-card:hover { border-color: #0d6efd; background: #f8fbff; }

    .account-card input[type="radio"]:checked + .account-card-body {
        border-left: 4px solid #0d6efd; padding-left: 12px;
    }

    .account-card-body { display: flex; flex-direction: column; gap: 6px; }

    .account-card-header { display: flex; justify-content: space-between; align-items: center; }

    .account-card-header .method { font-weight: 600; font-size: 15px; color: #0d6efd; }

    .account-row { display: flex; justify-content: space-between; font-size: 13px; }
    .account-row .label { color: #6c757d; font-weight: 500; }
    .account-row .value { font-weight: 600; color: #212529; }

    .account-card.client input[type="radio"]:checked + .account-card-body {
        border-left-color: #198754;
    }

    .badge { font-size: 11px; }

    .table td input[type="number"] { max-width: 120px; }

    #clientInvoicesTable,
    #clientInvoicesTable td,
    #clientInvoicesTable th { font-size: 13px; padding: 4px 8px; vertical-align: middle; }
    #clientInvoicesTable input[type="number"], #clientInvoicesTable input[type="checkbox"] { height: 28px; font-size: 13px; }
    #clientInvoicesTable .badge { font-size: 11px; padding: 2px 6px; }

    .existing-receipt { max-width: 200px; border: 1px solid #dee2e6; border-radius: 5px; padding: 5px; }


/* Extra Style */
    .spin {
        animation: spin 1s linear infinite;
    }
    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
    .table-success {
        background-color: rgba(25, 135, 84, 0.05) !important;
    }
    .table-success td {
        border-color: rgba(25, 135, 84, 0.1) !important;
    }
    #validationErrors {
        display: none;
        padding: 10px;
        margin: 10px 0;
        border-radius: 5px;
        background-color: #f8d7da;
        border: 1px solid #f5c6cb;
    }
    .account-card input[type="radio"]:checked + .account-card-body {
        border-left: 4px solid #0d6efd;
        padding-left: 12px;
        background-color: #f8fbff;
    }
    .account-card.client input[type="radio"]:checked + .account-card-body {
        border-left-color: #198754;
        background-color: rgba(25, 135, 84, 0.05);
    }
</style>

<div class="pagetitle">
    <h1>Edit Client Payment</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('receive-payment.index') }}">Client Payments</a></li>
            <li class="breadcrumb-item active">Edit Payment</li>
        </ol>
    </nav>
</div>

<section class="section">

    <div class="row mb-3">
        <div class="col-lg-12">
            <a class="btn btn-primary" href="{{ route('receive-payment.index') }}">
                &lt; Back
            </a>
        </div>
    </div>

    <div class="row">
        <form id="EditClientPaymentForm" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="payment_id" id="payment_id" value="{{ $payment->id }}">

            <!-- LEFT COLUMN -->
            <div class="col-lg-8">

                <!-- Client -->
                <div class="card mb-3">
                    <div class="card-body">
                        <div class="row mb-3 mt-3 align-items-center">
                            <label for="client_id" class="col-sm-3 col-form-label">Client</label>
                            <div class="col-sm-9">
                                <select class="form-control custom_select" name="client_id" id="client_id">
                                    <option value="">Select Client</option>
                                    @foreach ($clients as $client)
                                        <option value="{{ $client->id }}" {{ $payment->client_id == $client->id ? 'selected' : '' }}>
                                            {{ $client->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Payment Method -->
                <div class="card mb-3">
                    <div class="card-body">
                        <div class="row mt-3 align-items-center">
                            <label class="col-sm-3 col-form-label">
                                Payment Method <span class="text-danger">*</span>
                            </label>
                            <div class="col-sm-9">
                                <select class="form-control" id="payment_method_id" name="payment_method_id">
                                    <option value="">Select Payment Method</option>
                                    @foreach ($paymentMethods as $method)
                                        <option value="{{ $method->id }}" data-type="{{ strtolower($method->name) }}"
                                            {{ $payment->payment_method_id == $method->id ? 'selected' : '' }}>
                                            {{ $method->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Pay To (Client Accounts) -->
                <div class="card mb-3">
                    <div class="card-body">
                        <h6 class="card-title">Pay To (Client Accounts)</h6>
                        <div id="clientAccountsWrapper">
                            <div id="clientAccountsList"></div>
                        </div>
                    </div>
                </div>

                <!-- Pay From (Your Accounts) -->
                <div class="card mb-3">
                    <div class="card-body">
                        <h6 class="card-title">Pay From</h6>
                        <div id="userAccountsWrapper">
                            <div id="userAccountsList"></div>
                        </div>
                    </div>
                </div>

                <!-- Payment Details -->
                <div class="card mb-3">
                    <div class="card-body">
                        <h6 class="card-title">Payment Details</h6>
                        <div class="row mb-3 align-items-center">
                            <label for="payment_date" class="col-sm-3 col-form-label">Payment Date <span class="text-danger">*</span></label>
                            <div class="col-sm-9">
                                <input type="date" class="form-control" name="payment_date" id="payment_date" value="{{ $payment->payment_date }}">
                            </div>
                        </div>

                        <div class="row mb-3 align-items-center">
                            <label for="amount" class="col-sm-3 col-form-label">Amount <span class="text-danger">*</span></label>
                            <div class="col-sm-9">
                                <input type="number" class="form-control" name="amount" id="amount" step="0.01" min="0" placeholder="Enter payment amount" value="{{ $payment->amount }}">
                            </div>
                        </div>

                        <div class="row mb-3 align-items-center">
                            <label for="reference" class="col-sm-3 col-form-label">Reference</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="reference" id="reference" placeholder="Cheque no, transaction id, ref # (optional)" value="{{ $payment->reference }}">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Outstanding Invoices -->
                <div class="card mb-3">
                    <div class="card-body">
                        <h6 class="card-title">Outstanding Invoices</h6>
                        <table class="table table-sm table-bordered">
                            <thead class="bg-success text-white">
                                <tr>
                                    <th style="width:30px;"><input type="checkbox" id="selectAllInvoices"></th>
                                    <th>Invoice #</th>
                                    <th>Invoice Date</th>
                                    <th>Due Date</th>
                                    <th>Status</th>
                                    <th>Total</th>
                                    <th>Amount Paid</th>
                                    <th>Balance Due</th>
                                    <th>Apply</th>
                                </tr>
                            </thead>
                            <tbody id="clientInvoicesTable">
                                <!-- AJAX populated -->
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Notes & Receipt -->
                <div class="card mb-3">
                    <div class="card-body">
                        <div class="row mb-3 mt-3 align-items-center">
                            <label for="notes" class="col-sm-3 col-form-label">Notes</label>
                            <div class="col-sm-9">
                                <textarea class="form-control" name="notes" id="notes" placeholder="Additional notes (optional)">{{ $payment->notes }}</textarea>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-sm-3 col-form-label">Payment Receipt</label>
                            <div class="col-sm-9 d-flex align-items-center">
                                <label class="image-upload-container">
                                    <div id="imagePreview">
                                        @if($payment->receipt)
                                            <img src="{{ asset('storage/' . $payment->receipt) }}" alt="Receipt">
                                            <p class="mt-2">Current receipt (click to change)</p>
                                        @else
                                            <i class="bi bi-image upload-icon"></i>
                                            <p class="upload-text">Upload Receipt</p>
                                            <p>or drag it in</p>
                                            <p class="file-requirements">Max 20MB (JPG/PNG/PDF)</p>
                                        @endif
                                    </div>
                                    <input type="file" name="receipt" id="imageInput" accept="image/*,.pdf">
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="text-end">
                    <button type="submit" class="btn btn-primary">Update Payment</button>
                </div>
            </div>

            <!-- RIGHT SIDEBAR -->
            <div class="col-lg-4">
                <!-- Payment Info -->
                <div class="card mb-3 border-info">
                    <div class="card-body">
                        <h6 class="card-title">Payment Info</h6>
                        <div class="mb-2"><strong>Payment #:</strong> {{ $payment->payment_number }}</div>
                        <div class="mb-2"><strong>Original Amount:</strong> {{ number_format($payment->amount, 2) }}</div>
                        <div><strong>Date:</strong> {{ date('M d, Y', strtotime($payment->payment_date)) }}</div>
                    </div>
                </div>

                <!-- Client Balance -->
                <div class="card mb-3 border-danger">
                    <div class="card-body">
                        <h6 class="card-title">Client Balance</h6>
                        <h3 class="text-danger" id="clientBalance">0.00</h3>
                    </div>
                </div>

                <!-- Recent Payments -->
                <div class="card">
                    <div class="card-body">
                        <h6 class="card-title">Recent Payments</h6>
                        <ul class="list-group list-group-flush" id="recentPayments"></ul>
                    </div>
                </div>
            </div>

        </form>
    </div>
</section>

@endsection

@section('javascript')
  @include('customer.client-payments.edit-payment-js')
@endsection
