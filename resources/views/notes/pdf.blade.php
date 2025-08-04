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
            width: 100%;
            /* Use full width to prevent squeezing */
            margin-bottom: 18px;
        }

        .details-table td {
            vertical-align: top;
            padding-bottom: 4px;
        }

        .details-table .label {
            font-weight: bold;
            width: 100%;
            /* Corrected width for proper alignment */
            padding-right: 10px;
        }

        .details-table .value {
            width: 100%;
            /* Allocate remaining width for values */
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

        .banking-details {
            margin-top: 25px;
            font-size: 12px;
            line-height: 1.5;
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
                <td class="left" style="width:100%;">
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
                <td class="label">Original Insured</td>
                <td class="value">{{ $quote->insured_name }}</td>
            </tr>
            <tr>
                <td class="label">Reinsurer (RI & Order)</td>
                <td class="value">
                    @if ($quote->reinsurer)
                        @php
                            $reinsurers = json_decode($quote->reinsurer, true);
                            if (!is_array($reinsurers)) {
                                $reinsurers = [];
                            } // Ensure it's an array
                        @endphp
                        @foreach ($reinsurers as $reinsurer)
                            {{ $reinsurer['name'] }} ({{ $reinsurer['percentage'] }}%)<br>
                        @endforeach
                    @endif
                </td>
            </tr>
            <tr>
                <td class="label">Policy Type</td>
                <td class="value">{{ $quote->policy_name ?? '' }}</td>
            </tr>
            @if (!empty($note->PPW))
                <tr>
                    <td class="label">Premium Payment</td>
                    <td class="value">{{ $note->PPW }}</td>
                </tr>
            @endif
        </table>
        <table class="particulars-table">
            <thead>
                <tr>
                    <th style="text-align:left;">Particulars</th>
                    <th style="text-align:right;">Amount</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $particulars = $note->particulars;

                    // Check if the data is a string and needs decoding.
                    if (is_string($particulars)) {
                        $particulars = json_decode($particulars, true);
                    }

                    // Handle cases of double-encoding: if the first decode still results in a string, decode again.
                    if (is_string($particulars)) {
                        $particulars = json_decode($particulars, true);
                    }

                    // Final check to ensure we have an array, otherwise create an empty one to prevent errors.
                    if (!is_array($particulars)) {
                        $particulars = [];
                    }

                    $grandTotal = 0;
                    if ($note->credit_type === 'credit') {
                        // For credit notes, the total is the last value in the array.
                        $grandTotal = end($particulars);
                    } else {
                        // For debit notes, look for the specific 'Grand Total Due' key.
                        $grandTotal = $particulars['Grand Total Due'] ?? end($particulars);
                    }
                @endphp

                @if (!empty($particulars))
                    @foreach ($particulars as $key => $value)
                        @php
                            // Determine styling based on keywords in the key
                            $isSubItem = str_contains($key, 'Less:');
                            $isSubTotal = str_contains($key, 'Premium due from') || str_contains($key, 'Total due to');
                            $isGrandTotal = str_contains($key, 'Grand Total Due');
                            $isShare = str_contains($key, "'s share @");

                            $rowStyle = '';
                            $keyStyle = '';
                            if ($isSubItem) {
                                $keyStyle = 'padding-left: 25px;';
                            }
                            if ($isShare) {
                                $rowStyle = 'padding-top: 10px;';
                            }
                            if ($isSubTotal) {
                                $keyStyle = 'font-weight: bold;';
                            }
                            if ($isGrandTotal) {
                                $rowStyle = 'border-top: 2px solid #333;';
                                $keyStyle = 'font-weight: bold;';
                            }
                        @endphp
                        <tr style="{{ $rowStyle }}">
                            <td style="{{ $keyStyle }}">
                                {{-- For debit notes, remove the reinsurer name from the "Less" lines for cleaner look --}}
                                @if ($note->credit_type === 'debit' && $isSubItem)
                                    {{ trim(explode(' for ', $key)[0]) }}
                                @else
                                    {{ $key }}
                                @endif
                            </td>
                            <td
                                style="text-align:right; font-weight: {{ $isSubTotal || $isGrandTotal ? 'bold' : 'normal' }};">
                                @if (is_numeric($value))
                                    {{-- For "Less" items, show the number as positive since the description implies subtraction --}}
                                    {{ number_format(abs($value), 2) }}
                                @else
                                    {{ $value }}
                                @endif
                            </td>
                        </tr>
                    @endforeach
                @endif
            </tbody>
        </table>
        <div class="amount-words italic">
            (Total Amount — Rupees
            {{ ucwords(\NumberFormatter::create('en_IN', \NumberFormatter::SPELLOUT)->format(abs($grandTotal))) }}
            only)
        </div>
        <div class="amount-numbers">
            Total Amount Due: INR {{ number_format($grandTotal, 2) }}
        </div>
        @if (isset($bankingDetail) && $bankingDetail)
            <div class="banking-details">
                <span class="bold underline">Our Banking Details:</span><br>
                Bank: {{ $bankingDetail->Bank }}<br>
                Account No.- {{ $bankingDetail->Account_No }}<br>
                IFSC Code- {{ $bankingDetail->IFSC_Code }}<br>
                Branch: {{ $bankingDetail->Branch }}
            </div>
        @endif
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
