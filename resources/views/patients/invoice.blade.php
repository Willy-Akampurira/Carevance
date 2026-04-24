<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>
        {{ \App\Models\Setting::where('setting_key','invoice_prefix')->value('value') ?? '' }}
        {{ $record->invoice_number }}
    </title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 14px; color: #333; }
        .header { text-align: center; margin-bottom: 20px; }
        .logo { max-height: 80px; }
        .invoice-title { font-size: 24px; font-weight: bold; margin-top: 10px; }
        .meta, .patient-info { margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { border: 1px solid #ccc; padding: 8px; text-align: left; }
        th { background: #f5f5f5; }
        .total { text-align: right; font-size: 16px; font-weight: bold; }
        .footer-note { margin-top: 30px; font-size: 12px; text-align: center; color: #666; }
    </style>
</head>
<body>
    <!-- Header with Logo -->
    <div class="header">
        <img src="{{ public_path('storage/' . (\App\Models\Setting::where('setting_key','clinic_logo')->value('value') ?? 'logo.png')) }}" 
             alt="{{ \App\Models\Setting::where('setting_key','clinic_name')->value('value') ?? 'Supreme-Clinic' }} Logo" 
             class="logo">
        <div class="invoice-title">Invoice</div>
    </div>

    <!-- Invoice Metadata -->
    <div class="meta">
        <p><strong>Invoice Number:</strong> 
            {{ \App\Models\Setting::where('setting_key','invoice_prefix')->value('value') ?? '' }}{{ $record->invoice_number }}
        </p>
        <p><strong>Invoice Date:</strong> {{ \Carbon\Carbon::parse($record->invoice_date)->format('Y-m-d') }}</p>
        <p><strong>Status:</strong> {{ ucfirst($record->status) }}</p>
    </div>

    <!-- Patient Info -->
    <div class="patient-info">
        <p><strong>Patient:</strong> {{ $record->patient->name }}</p>
        <p><strong>Insurance Provider:</strong> {{ $record->insurance_provider ?? 'N/A' }}</p>
        <p><strong>Claim Number:</strong> {{ $record->claim_number ?? 'N/A' }}</p>
        <p><strong>Claim Status:</strong> {{ ucfirst($record->claim_status ?? 'pending') }}</p>
    </div>

    <!-- Items Table -->
    <table>
        <thead>
            <tr>
                <th>Description</th>
                <th>Qty</th>
                <th>Unit Price ({{ \App\Models\Setting::where('setting_key','invoice_currency')->value('value') ?? 'UGX' }})</th>
                <th>Total ({{ \App\Models\Setting::where('setting_key','invoice_currency')->value('value') ?? 'UGX' }})</th>
            </tr>
        </thead>
        <tbody>
            @foreach($record->items as $item)
                <tr>
                    <td>{{ $item->description }}</td>
                    <td>{{ $item->quantity }}</td>
                    <td>{{ number_format($item->unit_price, 2) }}</td>
                    <td>{{ number_format($item->total, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Grand Total -->
    @php
        $taxRate = (float) (\App\Models\Setting::where('setting_key','invoice_tax')->value('value') ?? 0);
        $discountEnabled = (bool) (\App\Models\Setting::where('setting_key','invoice_discount')->value('value') ?? false);
        $amount = $record->amount;
        $taxAmount = $amount * ($taxRate / 100);
        $grandTotal = $amount + $taxAmount;
        if ($discountEnabled && $record->discount) {
            $grandTotal -= $record->discount;
        }
    @endphp

    <p class="total">
        Grand Total: {{ number_format($grandTotal, 2) }} {{ \App\Models\Setting::where('setting_key','invoice_currency')->value('value') ?? 'UGX' }}
    </p>

    <!-- Notes -->
    @if($record->notes)
        <p><strong>Notes:</strong> {{ $record->notes }}</p>
    @endif

    <!-- Footer Note -->
    <div class="footer-note">
        {{ \App\Models\Setting::where('setting_key','invoice_footer_note')->value('value') }}
    </div>
</body>
</html>
