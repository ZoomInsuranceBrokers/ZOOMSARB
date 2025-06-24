<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Note PDF</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 13px; margin: 0; padding: 0; }
        .letterhead { width: 100%; margin: 0; padding: 0; }
        .letterhead img { width: 100%; height: auto; display: block; }
        .container { padding: 30px 40px 0 40px; }
        .info-table { width: 100%; margin-bottom: 20px; }
        .info-table td { vertical-align: top; }
        .bold { font-weight: bold; }
        .section { margin-bottom: 10px; }
        .details-table { width: 100%; margin-bottom: 18px; }
        .details-table td { vertical-align: top; padding-bottom: 2px; }
        .details-table .left-col { width: 50%; }
        .details-table .right-col { width: 50%; }
        .particulars-table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        .particulars-table th, .particulars-table td { border: 1px solid #333; padding: 6px 8px; }
        .particulars-table th { background: #e3f0ff; }
        .amount-words { margin-top: 18px; font-weight: bold; }
        .amount-numbers { font-size: 15px; font-weight: bold; margin-top: 6px; }
        .center { text-align: center; }
        .left { text-align: left; }
        .right { text-align: right; }
    </style>
</head>
<body>
    <div class="letterhead">
        <img src="{{ public_path('assets/images/letter up-01.png') }}">
    </div>
    <div class="container">
        <table class="info-table">
            <tr>
                <td class="left" style="width:33%;">
                    Invoice No. {{ $note->invoice_number }}
                </td>
                <td class="center" style="width:34%;">
                    <div class="bold" style="margin-bottom:2px;">{{ ucfirst($note->credit_type) }} Note</div>
                </td>
                <td class="right" style="width:33%;">
                    {{ \Carbon\Carbon::parse($note->date)->format('F d, Y') }}
                </td>
            </tr>
        </table>
        <table class="details-table">
            <tr>
                <td class="left-col">
                    <span class="bold">To,</span><br>
                    {{ $note->to }}<br>
                    {{ $note->PPW }}<br>
                    @if(isset($quote))
                        {{ $quote->address ?? '' }}<br>
                    @endif
                    <br>
                    <span class="bold">Reinsured</span> - {{ $note->reinsured }}<br>
                </td>
                <td class="right-col">
                    <span class="bold">Reinsurer (RI & Order)</span> - {{ $note->Reinsurer }}<br>
                    <span class="bold">Original Insured</span> - {{ $note->original_insured }}<br>
                    <span class="bold">Period of Insurance</span> -
                        @if(isset($quote) && !empty($quote->policy_period))
                            {{ $quote->policy_period }}
                        @else
                            {{ $note->period ?? '' }}
                        @endif
                    <br>
                    <span class="bold">Policy Type</span> - {{ $note->policy_type ?? 'Terrorism and Sabotage & Terrorism Liability Insurance' }}<br>
                    <span class="bold">PPW</span> - {{ $note->PPW }}
                </td>
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
                @php
                    $total = 0;
                    $lastKey = array_key_last($note->particulars);
                @endphp
                @foreach($note->particulars as $key => $value)
                    <tr @if(stripos($key, 'less') !== false) style="background:#e3f0ff;" @endif>
                        <td>{{ $key }}</td>
                        <td style="text-align:right;">
                            @if(is_numeric($value))
                                {{ number_format($value, 2) }}
                                @php $total = $key === $lastKey ? $value : $total; @endphp
                            @else
                                {{ $value }}
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="amount-words">
            (Total Amount due — Rupees {{ ucwords(\NumberFormatter::create('en_IN', \NumberFormatter::SPELLOUT)->format($total)) }} only)
        </div>
        <div class="amount-numbers">
            ({{ number_format($total, 2) }})
        </div>
    </div>
</body>
</html>
