<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Reinsurance Placement Slip</title>
    @php
        // Set default values if not passed from controller
        $currency = $currency ?? 'INR';
        $exchangeRate = $exchangeRate ?? 1;
        
        // Helper function to convert and format amounts
        // For USD: if rate = 84, then 1 USD = 84 INR, so INR amount / 84 = USD amount
        // For EUR: if rate = 90, then 1 EUR = 90 INR, so INR amount / 90 = EUR amount
        function convertAndFormat($amount, $currency, $exchangeRate) {
            if ($currency === 'INR') {
                $convertedAmount = (float)$amount;
            } else {
                // Convert FROM INR TO selected currency
                $convertedAmount = (float)$amount / $exchangeRate;
            }
            return $currency . ' ' . number_format($convertedAmount, 0) . ' ';
        }
        
        // Helper function to convert INR text and amounts in strings
        function convertInrTextAndAmounts($text, $currency, $exchangeRate) {
            if ($currency === 'INR' || empty($text)) {
                return $text;
            }
            
            // Pattern to match INR followed by numbers (with commas and spaces)
            $pattern = '/INR\s*([\d,\s]+)/i';
            
            return preg_replace_callback($pattern, function($matches) use ($currency, $exchangeRate) {
                // Clean the amount string - remove spaces and commas
                $cleanAmount = str_replace([',', ' '], '', $matches[1]);
                
                // Convert to float and then to target currency
                $inrAmount = (float)$cleanAmount;
                $convertedAmount = $inrAmount / $exchangeRate;
                
                // Format the converted amount
                return $currency . ' ' . number_format($convertedAmount, 0) . ' ';
            }, $text);
        }
    @endphp
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

        .risk-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 8px;
            margin-bottom: 8px;
            border: 1px solid #ddd;
        }

        .risk-table th {
            background-color: #f5f5f5;
            border: 1px solid #ddd;
            padding: 8px 6px;
            font-weight: bold;
            text-align: left;
            font-size: 11px;
        }

        .risk-table td {
            border: 1px solid #ddd;
            padding: 6px;
            font-size: 11px;
            vertical-align: top;
        }

        .risk-table .location-col {
            width: 40%;
        }

        .risk-table .amount-col {
            width: 20%;
            text-align: right;
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
                    <td class="label">Policy Name:</td>
                    <td class="value">
                        <div>{{ $quote->policy_name ?? '' }}</div>

                    </td>
                </tr>
                <tr>
                    <td class="label">Original Insured:</td>
                    <td class="value">{{ $quote->insured_name ?? '' }}</td>
                </tr>
                <tr>
                    <td class="label">Insured Address:</td>
                    <td class="value">{{ $quote->insured_address ?? '' }}</td>
                </tr>
                <tr>
                    <td class="label">Policy Period:</td>
                    <td class="value">
                       {{ $quote->policy_period ?? '' }}
                    </td>
                </tr>

                {{-- @if (!empty($quote->cedant))
                    <tr>
                        <td class="label">Cedant Name:</td>
                        <td class="value">{{ $quote->cedant ?? '' }}</td>
                    </tr>
                @endif
                @if (!empty($quote->reinsurer))
                    <tr>
                        <td class="label">Reinsurer Name:</td>
                        <td class="value">{{ $quote->reinsurer ?? '' }}</td>
                    </tr>
                @endif --}}
                <tr>
                    <td class="label">Occupancy:</td>
                    <td class="value">{{ $quote->occupancy ?? '' }}</td>
                </tr>
                <tr>
                    <td class="label">Risk Locations:</td>
                    <td class="value">
                        @if($quote->risk_location_as_per_annexure == 1)
                            As Per Annexure
                        @else
                            @if (!empty($quote->risk_locations))
                                @php
                                    $riskLocations = json_decode($quote->risk_locations, true);
                                @endphp
                                @if(is_array($riskLocations) && count($riskLocations) > 0)
                                    <table class="risk-table">
                                        <thead>
                                            <tr>
                                                <th class="location-col">Location</th>
                                                <th class="amount-col">Property Damage</th>
                                                <th class="amount-col">Business Interruption</th>
                                                <th class="amount-col">Total Sum Insured</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($riskLocations as $location)
                                                @if(isset($location['location']))
                                                    <tr>
                                                        <td class="location-col">{{ $location['location'] ?? '' }}</td>
                                                        <td class="amount-col">{{ convertAndFormat($location['property_damage'] ?? 0, $currency, $exchangeRate) }}</td>
                                                        <td class="amount-col">{{ convertAndFormat($location['business_interruption'] ?? 0, $currency, $exchangeRate) }}</td>
                                                        <td class="amount-col">{{ convertAndFormat($location['total_sum_insured'] ?? 0, $currency, $exchangeRate) }}</td>
                                                    </tr>
                                                @endif
                                            @endforeach
                                            @php
                                                $totalPropertyDamage = 0;
                                                $totalBusinessInterruption = 0;
                                                $grandTotal = 0;
                                                foreach ($riskLocations as $location) {
                                                    if(isset($location['location'])) {
                                                        $totalPropertyDamage += (float)($location['property_damage'] ?? 0);
                                                        $totalBusinessInterruption += (float)($location['business_interruption'] ?? 0);
                                                        $grandTotal += (float)($location['total_sum_insured'] ?? 0);
                                                    }
                                                }
                                            @endphp
                                            <tr style="background-color: #f9f9f9; font-weight: bold;">
                                                <td class="location-col">TOTAL</td>
                                                <td class="amount-col">{{ convertAndFormat($totalPropertyDamage, $currency, $exchangeRate) }}</td>
                                                <td class="amount-col">{{ convertAndFormat($totalBusinessInterruption, $currency, $exchangeRate) }}</td>
                                                <td class="amount-col">{{ convertAndFormat($grandTotal, $currency, $exchangeRate) }}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                @else
                                    No risk locations specified
                                @endif
                            @else
                                No risk locations specified
                            @endif
                        @endif
                    </td>
                </tr>
                <tr>
                    <td class="label">Jurisdiction:</td>
                    <td class="value">{{ $quote->jurisdiction ?? '' }}</td>
                </tr>
                <tr>
                    <td class="label">Property Damage:</td>
                    <td class="value">{{ convertAndFormat($quote->property_damage ?? 0, $currency, $exchangeRate) }}</td>
                </tr>
                <tr>
                    <td class="label">Business Interruption:</td>
                    <td class="value">{{ convertAndFormat($quote->business_interruption ?? 0, $currency, $exchangeRate) }}</td>
                </tr>
                <tr>
                    <td class="label">Total Sum Insured:</td>
                    <td class="value">{{ convertAndFormat((float)($quote->property_damage ?? 0) + (float)($quote->business_interruption ?? 0), $currency, $exchangeRate) }}</td>
                </tr>
                <tr>
                    <td class="label">Coverage:</td>
                    <td class="value">{{ convertInrTextAndAmounts($quote->coverage ?? '', $currency, $exchangeRate) }}</td>
                </tr>
                <tr>
                    <td class="label">Limit of Indemnity:</td>
                    <td class="value">
                        @if (!empty($quote->limit_of_indemnity))
                            @foreach (json_decode($quote->limit_of_indemnity, true) as $lim)
                                {{ convertInrTextAndAmounts($lim, $currency, $exchangeRate) }}
                                <br>
                            @endforeach
                        @endif
                    </td>
                </tr>
                <tr>
                    <td class="label">Indemnity Period:</td>
                    <td class="value">{{ convertInrTextAndAmounts($quote->indemnity_period ?? '', $currency, $exchangeRate) }}</td>
                </tr>
                <tr>
                    <td class="label">Additional Covers:</td>
                    <td class="value">
                        @if (!empty($quote->additional_covers))
                            @foreach (json_decode($quote->additional_covers, true) as $lim)
                                {{ convertInrTextAndAmounts($lim, $currency, $exchangeRate) }}
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
                                {{ convertInrTextAndAmounts($lim, $currency, $exchangeRate) }}
                                <br>
                            @endforeach
                        @endif
                    </td>
                </tr>

                <tr>
                    <td class="label">Premium:</td>
                    <td class="value">{{ convertInrTextAndAmounts($quote->premium ?? '', $currency, $exchangeRate) }}</td>
                </tr>
                <tr>
                    <td class="label">Support:</td>
                    <td class="value">{{ convertInrTextAndAmounts($quote->support ?? '', $currency, $exchangeRate) }}</td>
                </tr>
            </table>

        </div>
        <div>
            {!! convertInrTextAndAmounts($quote->policy_wording ?? '', $currency, $exchangeRate) !!}
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
