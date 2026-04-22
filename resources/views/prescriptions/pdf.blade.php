<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Prescription</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
        .header { text-align: center; margin-bottom: 20px; }
        .details { margin-bottom: 15px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #000; padding: 6px; text-align: left; }
        .logo { width: 120px; margin-bottom: 10px; }
    </style>
</head>
<body>
    <div class="header">
        <img src="{{ public_path('images/logo.png') }}" class="logo" alt="Carevance Logo">
        <h2>Carevance Prescription</h2>
    </div>

    <div class="details">
        <strong>Patient:</strong> {{ $prescription->patient->name }} <br>
        <strong>Issued By:</strong> {{ $prescription->issued_by }} <br>
        <strong>Status:</strong> {{ ucfirst($prescription->status) }} <br>
        <strong>Notes:</strong> {{ $prescription->notes }}
    </div>

    <table>
        <thead>
            <tr>
                <th>Drug</th>
                <th>Quantity</th>
                <th>Unit</th>
                <th>Dosage</th>
                <th>Frequency</th>
                <th>Duration</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>{{ $prescription->stockLot->drug->name }}</td>
                <td>{{ $prescription->quantity }}</td>
                <td>{{ $prescription->stockLot->unit }}</td>
                <td>{{ $prescription->dosage }}</td>
                <td>{{ $prescription->frequency }}</td>
                <td>{{ $prescription->duration_days }} days</td>
            </tr>
        </tbody>
    </table>

    <br><br>
    <div>
        <strong>Signature: ________________________</strong>
    </div>
</body>
</html>
