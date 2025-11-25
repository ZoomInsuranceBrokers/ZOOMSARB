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
            Reinsurance Placement Slip</h2>
        <div class="section">
            <table class="section-table">
                <tr>
                    <td class="label">Policy Name:</td>
                    <td class="value">
                        <div>{{ $placementSlip->policy_name ?? '' }}</div>

                    </td>
                </tr>
                <tr>
                    <td class="label">Insured:</td>
                    <td class="value">{{ $placementSlip->insured_name ?? '' }}</td>
                </tr>
                <tr>
                    <td class="label">Reinsured:</td>
                    <td class="value">{{ $quote->cedant ?? '' }}</td>
                </tr>
                <tr>
                    <td class="label">Insured Address:</td>
                    <td class="value">{{ $placementSlip->insured_address ?? '' }}</td>
                </tr>
                <tr>
                    <td class="label">Policy Period:</td>
                    <td class="value">
                        {{ $placementSlip->policy_period ?? '' }}
                    </td>
                </tr>


                <tr>
                    <td class="label">Reinsurer:</td>
                    <td class="value">{{ $placementSlip->reinsurer_name ?? '' }}</td>
                </tr>
                <tr>
                    <td class="label">Reinsurer Address:</td>
                    <td class="value">{{ $placementSlip->to ?? '' }}</td>
                </tr>
                <tr>
                    <td class="label">Risk Location Address:</td>
                    <td class="value">
                        @if (!empty($placementSlip->risk_locations) && $placementSlip->risk_location_as_per_annexure == 0)
                            @foreach (json_decode($placementSlip->risk_locations, true) as $location)
                                {{ $location['location'] ?? '' }}<br>
                            @endforeach
                        @else
                            As Per Annexure
                        @endif
                    </td>
                </tr>
                <tr>
                    <td class="label">Occupancy:</td>
                    <td class="value">{{ $placementSlip->occupancy ?? '' }}</td>
                </tr>
                <tr>
                    <td class="label">Risk Locations:</td>
                    <td class="value">
                        @if ($placementSlip->risk_location_as_per_annexure == 1)
                            As Per Annexure
                        @else
                            @if (!empty($placementSlip->risk_locations))
                                @php
                                    $riskLocations = json_decode($placementSlip->risk_locations, true);
                                @endphp
                                @if (is_array($riskLocations) && count($riskLocations) > 0)
                                    <table style="width: 100%; border-collapse: collapse; margin-top: 5px;">
                                        <thead>
                                            <tr>
                                                <th
                                                    style="border: 1px solid #333; padding: 5px; text-align: left; font-weight: bold;">
                                                    Location</th>
                                                <th
                                                    style="border: 1px solid #333; padding: 5px; text-align: right; font-weight: bold;">
                                                    Property Damage</th>
                                                <th
                                                    style="border: 1px solid #333; padding: 5px; text-align: right; font-weight: bold;">
                                                    Business Interruption</th>
                                                <th
                                                    style="border: 1px solid #333; padding: 5px; text-align: right; font-weight: bold;">
                                                    Total Sum Insured</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($riskLocations as $location)
                                                @if (isset($location['location']))
                                                    <tr>
                                                        <td style="border: 1px solid #333; padding: 5px;">
                                                            {{ $location['location'] ?? '' }}</td>
                                                        <td
                                                            style="border: 1px solid #333; padding: 5px; text-align: right;">
                                                            {{ $placementSlip->currency_type ?? 'INR' }}
                                                            {{ number_format((float) ($location['property_damage'] ?? 0), 2) }}
                                                        </td>
                                                        <td
                                                            style="border: 1px solid #333; padding: 5px; text-align: right;">
                                                            {{ $placementSlip->currency_type ?? 'INR' }}
                                                            {{ number_format((float) ($location['business_interruption'] ?? 0), 2) }}
                                                        </td>
                                                        <td
                                                            style="border: 1px solid #333; padding: 5px; text-align: right;">
                                                            {{ $placementSlip->currency_type ?? 'INR' }}
                                                            {{ number_format((float) ($location['total_sum_insured'] ?? 0), 2) }}
                                                        </td>
                                                    </tr>
                                                @endif
                                            @endforeach
                                        </tbody>
                                    </table>
                                @endif
                            @endif
                        @endif
                    </td>
                </tr>
                <tr>
                    <td class="label">Jurisdiction:</td>
                    <td class="value">{{ $placementSlip->jurisdiction ?? '' }}</td>
                </tr>
                <tr>
                    <td class="label">Property Damage:</td>
                    <td class="value"> {{ $placementSlip->currency_type ?? 'INR' }}
                        {{ number_format((float) ($placementSlip->property_damage ?? 0), 2) }}</td>
                </tr>
                <tr>
                    <td class="label">Business Interruption:</td>
                    <td class="value"> {{ $placementSlip->currency_type ?? 'INR' }}
                        {{ number_format((float) ($placementSlip->business_interruption ?? 0), 2) }}</td>
                </tr>
                <tr>
                    <td class="label">Total Sum Insured:</td>
                    <td class="value"> {{ $placementSlip->currency_type ?? 'INR' }}
                        {{ number_format((float) ($placementSlip->total_sum_insured ?? 0), 2) }}
                    </td>
                </tr>
                <tr>
                    <td class="label">Coverage:</td>
                    <td class="value">{{ $placementSlip->coverage ?? '' }}</td>
                </tr>
                <tr>
                    <td class="label">Original Policy wordings:</td>
                    <td class="value">{{ $placementSlip->coverage ?? '' }}</td>
                </tr>
                <tr>
                    <td class="label">Other Clauses:</td>
                    <td class="value">
                        @if (!empty($placementSlip->additional_covers))
                            @foreach (json_decode($placementSlip->additional_covers, true) as $cover)
                                @if (!empty($cover))
                                    {{ $cover }}<br>
                                @endif
                            @endforeach
                        @endif
                    </td>
                </tr>
                <tr>
                    <td class="label">Limit of Liability:</td>
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
                    <td class="label">Deductible:</td>
                    <td class="value">
                        @if (!empty($placementSlip->deductibles))
                            @foreach (json_decode($placementSlip->deductibles, true) as $deductible)
                                @if (!empty($deductible))
                                    {{ $deductible }}<br>
                                @endif
                            @endforeach
                        @endif
                    </td>
                </tr>
                <tr>
                    <td class="label">Claims:</td>
                    <td class="value">{{ $placementSlip->claims ?? '' }}</td>
                </tr>

                <tr>
                    <td class="label">Premium:</td>
                    <td class="value">{{ $placementSlip->premium ?? '' }}</td>
                </tr>
                    @if(!empty($placementSlip->ppw_details))
                    <tr>
                        <td class="label">PPW:</td>
                        <td class="value">{{ $placementSlip->ppw_details }}</td>
                    </tr>
                    @elseif(!empty($placementSlip->ppc_details))
                    <tr>
                        <td class="label">PPC:</td>
                        <td class="value">{{ $placementSlip->ppc_details }}</td>
                    </tr>
                    @endif
                <tr>
                    <td class="label">Reinsurer Share:</td>
                    <td class="value">
                        @if (!empty($quote->reinsurer))
                            @php
                                $reinsurers = json_decode($quote->reinsurer, true);
                                $currentReinsurer = null;
                                foreach ($reinsurers as $reinsurer) {
                                    if ($reinsurer['name'] === $placementSlip->reinsurer_name) {
                                        $currentReinsurer = $reinsurer;
                                        break;
                                    }
                                }
                            @endphp
                            @if ($currentReinsurer)
                                {{ number_format($currentReinsurer['percentage'], 3) }}% of 100%
                            @endif
                        @endif
                    </td>
                </tr>
                <tr>
                    <td class="label">Total Deductions:</td>
                    <td class="value">
                        @if (!empty($quote->reinsurer) && $currentReinsurer)
                            {{ number_format($currentReinsurer['brokerage'] + ($currentReinsurer['ceding_commission'] ?? 0), 1) }}%
                            (RI Brokerage {{ number_format($currentReinsurer['brokerage'], 1) }}% +
                            {{ number_format($currentReinsurer['ceding_commission'] ?? 0, 1) }}% ceding commission)
                        @endif
                    </td>
                </tr>
                <tr>
                    <td class="label">RI Broker:</td>
                    <td class="value">Zoom Insurance Brokers Pvt. Ltd.</td>
                </tr>
                <tr>
                    <td class="label">Support:</td>
                    <td class="value">{{ $placementSlip->support ?? '' }}</td>
                </tr>
            </table>

        </div>

        @if ($placementSlip->risk_location_as_per_annexure == 1)
            {!! $quote->policy_wording ?? '' !!}
        @endif
        @if (!empty($placementSlip->policy_wording))
            <div>
                {!! $placementSlip->policy_wording !!}
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
