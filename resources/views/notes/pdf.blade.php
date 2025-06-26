<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Debit Note PDF</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 13px;
            margin: 0;
            padding: 0;
        }

        .letterhead {
            width: 100%;
            margin: 0;
            padding: 0;
        }

        .letterhead img {
            width: 100%;
            height: auto;
            display: block;
        }

        .container {
            padding: 30px 40px 0 40px;
        }

        .note-title {
            text-align: center;
            font-weight: bold;
            font-size: 20px;
            text-decoration: underline;
            margin-top: 18px;
            margin-bottom: 10px;
        }

        .info-table {
            width: 100%;
            margin-bottom: 20px;
        }

        .info-table td {
            vertical-align: top;
        }

        .bold {
            font-weight: bold;
        }

        .center {
            text-align: center;
        }

        .left {
            text-align: left;
        }

        .right {
            text-align: right;
        }

        .to-section {
            margin-bottom: 10px;
        }

        .details-table {
            width: 60%;
            margin-bottom: 18px;
        }

        .details-table td {
            vertical-align: top;
            padding-bottom: 2px;
            padding-right: 12px;
        }

        .details-table .label {
            font-weight: bold;
            width: 40%;
        }

        .details-table .value {
            width: 100%;
            margin-left: 70px;
        }

        .particulars-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .particulars-table th,
        .particulars-table td {
            border: 1px solid #333;
            padding: 6px 8px;
        }

        .particulars-table th {
            background: #e3f0ff;
        }

        .amount-words {
            margin-top: 18px;
            font-weight: bold;
            font-style: italic;
        }

        .amount-numbers {
            font-size: 15px;
            font-weight: bold;
            margin-top: 6px;
        }

        .footer {
            width: 100%;
            position: fixed;
            bottom: 0;
            left: 0;
        }

        .footer-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 12px;
        }

        .footer-table td {
            padding: 8px 12px;
            vertical-align: top;
        }

        .footer-left {
            background: #e3f0ff;
            color: #222;
            width: 60%;
        }

        .footer-right {
            background: #2e3192;
            color: #fff;
            width: 40%;
            text-align: right;
        }

        .footer-logo {
            height: 22px;
            vertical-align: middle;
            margin-left: 10px;
        }

        .italic {
            font-style: italic;
        }

        .underline {
            text-decoration: underline;
        }
    </style>
</head>

<body>
    <div class="letterhead">
        <img src="{{ public_path('assets/images/letter up-01.png') }}">
    </div>
    <div class="note-title">
        {{ ucfirst($note->credit_type) }} Note
    </div>
    <div class="container">
        <table class="info-table">
            <tr>
                <td class="left" style="width:33%;">
                    Invoice No. {{ $note->invoice_number }}
                </td>
                <td class="center" style="width:34%;"></td>
                <td class="right" style="width:33%;">
                    {{ \Carbon\Carbon::parse($note->date)->format('F jS, Y') }}
                </td>
            </tr>
        </table>
        <div class="to-section">
            <span class="bold">To,</span><br>
            {{ $note->to }}<br>
            @if (isset($quote))
                {{ $quote->address ?? '' }}<br>
            @endif
        </div>
        <table class="details-table">
            <tr>
                <td class="label">Reinsurer (RI & Order)</td>
                <td class="value">-</td>
                <td class="value">{!! nl2br(e($note->Reinsurer)) !!}</td>
            </tr>
            <tr>
                <td class="label">Reinsured</td>
                <td class="value">-</td>
                <td class="value">{{ $note->reinsured }}</td>
            </tr>
            <tr>
                <td class="label">Policy Type</td>
                <td class="value">-</td>
                <td class="value">{{ $note->policy_type ?? 'Terrorism and Sabotage & Terrorism Liability Insurance' }}
                </td>
            </tr>
            <tr>
                <td class="label">PPW</td>
                <td class="value">-</td>
                <td class="value">{{ $note->PPW }}</td>
            </tr>
        </table>
        <table class="particulars-table">
            <thead>
                <tr>
                    <th>Particulars</th>
                    <th>Amount (INR)</th>
                </tr>
            </thead>
            <tbody>
                @php $total = 0; @endphp
                @foreach ($note->particulars as $key => $value)
                    <tr @if (stripos($key, 'less') !== false) style="background:#e3f0ff;" @endif>
                        <td @if (stripos($key, 'premium due from') !== false || stripos($key, 'total amount due') !== false) class="bold" @endif>
                            {!! stripos($key, 'premium due from') !== false || stripos($key, 'total amount due') !== false
                                ? '<span class="bold underline">' . $key . '</span>'
                                : $key !!}
                        </td>
                        <td style="text-align:right;">
                            @if (is_numeric($value))
                                {{ number_format($value, 2) }}
                                @php $total += $value; @endphp
                            @else
                                {{ $value }}
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="amount-words italic">
            (Total Amount — Rupees
            {{ ucwords(\NumberFormatter::create('en_IN', \NumberFormatter::SPELLOUT)->format($total)) }} only)
        </div>
        <div class="amount-numbers">
            ({{ number_format($total, 2) }})
        </div>
    </div>
    <div class="footer">
        <table class="footer-table">
            <tr>
                <td class="footer-left">
                    <span class="bold">Zoom Insurance Brokers Pvt. Ltd.</span><br>
                    IRDAI Reg. No: 389 | Licence Category: Composite<br>
                    Validity Period: 02/01/2024 to 01/01/2027<br>
                    CIN NO: U66000HR2010PTC039589
                </td>
                <td class="footer-right">
                    D-104, Udyog Vihar Phase V, Gurugram, Haryana-122016 INDIA<br>
                    +91-124 4203151<br>
                    info@zoominsurancebrokers.com<br>
                    www.zoominsurancebrokers.com
                    <img src="{{ public_path('assets/images/letter up-01.png') }}" class="footer-logo">
                </td>
            </tr>
        </table>
    </div>
</body>

</html>
