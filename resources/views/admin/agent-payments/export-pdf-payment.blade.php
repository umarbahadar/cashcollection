@php
    // Optional: business info if you want
    $business = null; // You can set a company name here if needed
    $logoData = null;

    $fullPath = public_path('front-theme/assets/img/logo.jpg');
    if (file_exists($fullPath)) {
        $imageData = base64_encode(file_get_contents($fullPath));
        $ext  = pathinfo($fullPath, PATHINFO_EXTENSION);
        $mime = $ext === 'jpg' ? 'jpeg' : $ext;
        $logoData = "data:image/{$mime};base64,{$imageData}";
    }
@endphp

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Cash Collection Receipt</title>
    <style>
        * { font-family: dejavusans, sans-serif; font-size: 10pt; }
        body { padding: 25px; color: #000; }
        h2, h3, h4 { margin-bottom: 8px; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 15px; }
        table th, table td { border: 1px solid #000; padding: 6px; }
        table th { font-weight: bold; background: #f2f2f2; }
        .no-border td { border: none; }
        .amount-box { font-size: 14pt; font-weight: bold; margin-top: 10px; }
        .footer { position: fixed; bottom: 20px; left: 25px; right: 25px; text-align: center; font-size: 9pt; }
    </style>
</head>
<body>

<!-- HEADER -->
<table class="no-border">
    <tr>
        <td width="50%">
            @if($logoData)
                <img src="{{ $logoData }}" style="max-height:80px; margin-bottom:10px;">
            @endif
            <strong>{{ $business?->business_name ?? 'Company Name' }}</strong>
        </td>

        <td width="50%" class="text-right">
            <h2>CASH COLLECTION RECEIPT</h2>
            <strong>Collection #:</strong> {{ $collection->id }}<br>
            <strong>Date:</strong> {{ $collection->collection_date }}<br>

            <div class="amount-box">
                Amount Collected: {{ number_format($collection->amount, 2) }}
            </div>
        </td>
    </tr>
</table>

<hr>

<!-- AGENT INFO -->
<h4>Agent Information</h4>
<table>
    <tr>
        <th>Name</th>
        <td>{{ $collection->agent?->name ?? '-' }}</td>
    </tr>
</table>

<!-- ACCOUNT DETAILS -->
<h4>Account Details</h4>
<table>
    <tr>
        <th>Account</th>
        <td>{{ $ledger?->account?->name ?? '-' }}</td>
    </tr>
    <tr>
        <th>Type</th>
        <td>{{ $ledger?->type ?? '-' }}</td>
    </tr>
</table>

@if($collection->notes)
    <h4>Notes</h4>
    <p>{{ $collection->notes }}</p>
@endif

<div class="footer">
    Generated on {{ now()->format('Y-m-d') }}
</div>

</body>
</html>