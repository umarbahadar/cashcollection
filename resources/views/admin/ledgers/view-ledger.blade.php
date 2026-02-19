@extends('admin.layouts.admin')
@section('title', 'View Ledger Entry')
@section('content')

<div class="pagetitle">
    <h1>Ledger Entry Details</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('dashboard') }}">Home</a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('ledger.index') }}">Ledgers</a>
            </li>
            <li class="breadcrumb-item active">Ledger Details</li>
        </ol>
    </nav>
</div>

<section class="section">

    <!-- Back Button -->
    <div class="row mb-3">
        <div class="col-lg-12">
            <a class="btn btn-primary" href="{{ route('ledger.index') }}">
                &lt; Back
            </a>
        </div>
    </div>

    <div class="row">

        <!-- LEFT COLUMN -->
        <div class="col-lg-8">

            <!-- Collection Info -->
            <div class="card mb-3">
                <div class="card-body">
                    <h5 class="card-title">Collection Information</h5>

                    <div class="row mb-2">
                        <label class="col-sm-4 col-form-label">Agent</label>
                        <div class="col-sm-8">
                            <span class="fw-bold">
                                {{ $collection->agent->name ?? '--' }}
                            </span>
                        </div>
                    </div>

                    <div class="row mb-2">
                        <label class="col-sm-4 col-form-label">Collection Date</label>
                        <div class="col-sm-8">
                            <span>{{ $collection->collection_date }}</span>
                        </div>
                    </div>

                    <div class="row mb-2">
                        <label class="col-sm-4 col-form-label">Amount</label>
                        <div class="col-sm-8">
                            <span class="fw-bold fs-5 text-success">
                                {{ number_format($collection->amount, 2) }}
                            </span>
                        </div>
                    </div>

                    <div class="row mb-2">
                        <label class="col-sm-4 col-form-label">Notes</label>
                        <div class="col-sm-8">
                            <span>{{ $collection->notes ?? '--' }}</span>
                        </div>
                    </div>

                </div>
            </div>

            <!-- Ledger Info -->
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Ledger Entry</h5>

                    @if($ledger)

                        <div class="row mb-2">
                            <label class="col-sm-4 col-form-label">Account</label>
                            <div class="col-sm-8">
                                <span>{{ $ledger->account->name ?? '--' }}</span>
                            </div>
                        </div>

                        <div class="row mb-2">
                            <label class="col-sm-4 col-form-label">Entry Type</label>
                            <div class="col-sm-8">
                                <span class="badge bg-{{ $ledger->type == 'credit' ? 'success' : 'danger' }}">
                                    {{ ucfirst($ledger->type) }}
                                </span>
                            </div>
                        </div>

                        <div class="row mb-2">
                            <label class="col-sm-4 col-form-label">Ledger Amount</label>
                            <div class="col-sm-8">
                                <span>{{ number_format($ledger->amount, 2) }}</span>
                            </div>
                        </div>

                        <div class="row mb-2">
                            <label class="col-sm-4 col-form-label">Description</label>
                            <div class="col-sm-8">
                                <span>{{ $ledger->description ?? '--' }}</span>
                            </div>
                        </div>

                    @else
                        <div class="alert alert-warning">
                            No ledger entry found for this collection.
                        </div>
                    @endif

                </div>
            </div>

        </div>

        <!-- RIGHT COLUMN -->
        <div class="col-lg-4">

            <div class="card">
                <div class="card-body">

                    <h5 class="card-title">Recent Collections (Same Agent)</h5>

                    <ul class="list-group">
                        @forelse($recentCollections as $item)
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <a href="{{ route('ledger.view', $item->id) }}"
                                   class="text-decoration-none fw-medium"
                                   target="_blank">
                                    {{ $item->collection_date }}
                                </a>
                                <strong>
                                    {{ number_format($item->amount, 2) }}
                                </strong>
                            </li>
                        @empty
                            <li class="list-group-item text-muted">
                                No recent collections
                            </li>
                        @endforelse
                    </ul>

                    <div class="mt-4">
                        <a href="{{ route('ledger.pdf', $collection->id) }}" class="btn btn-primary w-100 mb-3" target="_blank">
                            Download PDF
                        </a>
                    </div>

                </div>
            </div>

        </div>

    </div>
</section>

@endsection

@section('javascript')
  @include('admin.ledgers.view-ledger-js')
@endsection