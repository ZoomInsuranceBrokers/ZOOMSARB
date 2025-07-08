<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Reinsurance Placement Slip</title>
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
        .section {
            margin-top: 32px;
            margin-bottom: 32px;
        }
        .section-table {
            width: 100%;
            border-collapse: collapse;
        }
        .section-table td {
            padding: 7px 10px 7px 0;
            vertical-align: top;
        }
        .section-table .label {
            font-weight: bold;
            width: 180px;
            white-space: nowrap;
        }
        .section-table .value {
            width: 70%;
        }
        .summary-title {
            font-weight: bold;
            font-size: 15px;
            margin-bottom: 10px;
            margin-top: 30px;
        }
        .summary-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .summary-table td {
            padding: 6px 10px 6px 0;
            vertical-align: top;
        }
        .summary-table .label {
            font-weight: bold;
            width: 200px;
            white-space: nowrap;
        }
        .summary-table .value {
            width: 70%;
        }
        .policy-wording-title {
            font-weight: bold;
            font-size: 15px;
            margin-bottom: 10px;
            margin-top: 30px;
        }
        .footer {
            width: 100%;
            margin-top: 40px;
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
        .center {
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="letterhead">
        <img src="{{ public_path('assets/images/letter up-01.png') }}">
    </div>
    <div class="container">
        <h2 class="center" style="margin: 30px 0 30px 0; font-size: 18px; font-weight: bold; text-decoration: underline;">
            Reinsurance Quote Slip</h2>
        <div class="section">
            <table class="section-table">
                <tr>
                    <td class="label">TYPE:</td>
                    <td class="value">
                        <div>{{ $quote->policy_name ?? '' }}</div>

                    </td>
                </tr>
                <tr>
                    <td class="label">ORIGINAL INSURED:</td>
                    <td class="value">{{ $quote->insured_name ?? '' }}</td>
                </tr>
                <tr>
                    <td class="label">INSURED ADDRESS:</td>
                    <td class="value">{{ $quote->insured_address ?? '' }}</td>
                </tr>
                <tr>
                    <td class="label">PERIOD:</td>
                    <td class="value">
                        Effective from - To: {{ $quote->policy_period ?? '' }}<br>
                        <br>
                        (Both days inclusive at the location of the Property Reinsured.)
                    </td>
                </tr>
                <tr>
                    <td class="label">Policy Name:</td>
                    <td class="value">{{ $quote->policy_name ?? '' }}</td>
                </tr>
                <tr>
                    <td class="label">Cedant Name:</td>
                    <td class="value">{{ $quote->cedant ?? '' }}</td>
                </tr>
                <tr>
                    <td class="label">Reinsurer Name:</td>
                    <td class="value">{{ $quote->reinsurer ?? '' }}</td>
                </tr>
                <tr>
                    <td class="label">Occupancy:</td>
                    <td class="value">{{ $quote->occupancy ?? '' }}</td>
                </tr>
                <tr>
                    <td class="label">Risk Locations:</td>
                    <td class="value">
                        @if (!empty($quote->risk_locations))
                            @foreach (json_decode($quote->risk_locations, true) as $loc)
                                {{ $loc }}
                                <br>
                            @endforeach
                        @endif
                    </td>
                </tr>
                <tr>
                    <td class="label">Jurisdiction:</td>
                    <td class="value">{{ $quote->jurisdiction ?? '' }}</td>
                </tr>
                <tr>
                    <td class="label">Property Damage:</td>
                    <td class="value">{{ $quote->property_damage ?? '' }}</td>
                </tr>
                <tr>
                    <td class="label">Business Interruption:</td>
                    <td class="value">{{ $quote->business_interruption ?? '' }}</td>
                </tr>
                <tr>
                    <td class="label">Total Sum Insured:</td>
                    <td class="value">{{ $quote->Total_sum_insured ?? '' }}</td>
                </tr>
                <tr>
                    <td class="label">Coverage:</td>
                    <td class="value">{{ $quote->coverage ?? '' }}</td>
                </tr>
                <tr>
                    <td class="label">Limit of Indemnity:</td>
                    <td class="value">
                        @if (!empty($quote->limit_of_indemnity))
                            @foreach (json_decode($quote->limit_of_indemnity, true) as $lim)
                                {{ $lim }}
                                <br>
                            @endforeach
                        @endif
                    </td>
                </tr>
                <tr>
                    <td class="label">Indemnity Period:</td>
                    <td class="value">{{ $quote->indemnity_period ?? '' }}</td>
                </tr>
                <tr>
                    <td class="label">Additional Covers:</td>
                    <td class="value">
                        @if (!empty($quote->additional_covers))
                            @foreach (json_decode($quote->additional_covers, true) as $lim)
                                {{ $lim }}
                                <br>
                            @endforeach
                        @endif
                    </td>
                </tr>
                <tr>
                    <td class="label">Claims:</td>
                    <td class="value">{{ $quote->claims ?? '' }}</td>
                </tr>
                <tr>
                    <td class="label">Deductibles:</td>
                    <td class="value">
                        @if (!empty($quote->deductibles))
                            @foreach (json_decode($quote->deductibles, true) as $lim)
                                {{ $lim }}
                                <br>
                            @endforeach
                        @endif
                    </td>
                </tr>
                <tr>
                    <td class="label">Claims:</td>
                    <td class="value">{{ $quote->claims ?? '' }}</td>
                </tr>
                <tr>
                    <td class="label">Premium:</td>
                    <td class="value">{{ $quote->premium ?? '' }}</td>
                </tr>
            </table>
        </div>
        <div>
            {!! $quote->policy_wording ?? '' !!}
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
