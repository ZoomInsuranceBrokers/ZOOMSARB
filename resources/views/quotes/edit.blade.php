@extends('layouts.app')

@section('title', 'Edit Quote')

@section('content')
    <div style="max-width: 1050px; margin: 0 auto;">
        <div
            style="background: linear-gradient(135deg, #e3f0ff 0%, #f7faff 100%); border-radius: 18px; box-shadow: 0 2px 16px #2e319222; padding: 2.5rem 2rem; margin-bottom: 2rem;">
            <h2 style="text-align:center; color:#2e3192; font-weight:700; margin-bottom:1.5rem;">Edit Quote Slip</h2>
            <form method="POST" action="{{ route('quotes.update', $quote->id) }}">
                @csrf
                @method('PUT')
                <script>
                    function updateCurrencyLabels() {
                        var currencySelect = document.getElementById('currency');
                        var selected = currencySelect.options[currencySelect.selectedIndex].text;
                        var currencyLabels = document.querySelectorAll('.currency-label');
                        var placeholders = {
                            'INR': 'INR',
                            'Dollar': 'Dollar',
                            'Euro': 'Euro'
                        };
                        currencyLabels.forEach(function(label) {
                            label.textContent = placeholders[selected];
                        });
                        // Update placeholders for number inputs
                        var numberInputs = document.querySelectorAll('input[type="number"]');
                        numberInputs.forEach(function(input) {
                            if (input.placeholder) {
                                input.placeholder = input.placeholder.replace(/Rupee|Dollar|Euro/, placeholders[selected]);
                            }
                        });
                    }
                    document.addEventListener('DOMContentLoaded', function() {
                        updateCurrencyLabels();
                    });
                </script>
                <div style="display: flex; flex-wrap: wrap; gap: 1.5rem;">
                    <div style="flex:1 1 400px;">
                        <label for="insured_name" style="color:#2e3192; font-weight:600;">Insured Name</label>
                        <input type="text" id="insured_name" name="insured_name" class="form-input"
                            value="{{ $quote->insured_name }}">
                    </div>
                    <div style="flex:1 1 400px;">
                        <label for="insured_address" style="color:#2e3192; font-weight:600;">Insured Address</label>
                        <input type="text" id="insured_address" name="insured_address" class="form-input"
                            value="{{ $quote->insured_address }}">
                    </div>
                </div>
                <div style="display: flex; flex-wrap: wrap; gap: 1.5rem; margin-top:1.2rem;">
                    <div style="flex:1 1 400px;">
                        <label for="policy_name" style="color:#2e3192; font-weight:600;">Policy Name</label>
                        <select id="policy_name" name="policy_name" class="form-input">
                            <option value="">Select Policy</option>
                            <option value="Terrorism and Sabotage and Terrorism Liability Insurance"
                                {{ $quote->policy_name == 'Terrorism and Sabotage and Terrorism Liability Insurance' ? 'selected' : '' }}>
                                Terrorism and Sabotage and Terrorism Liability Insurance</option>

                            <option value="Professional Indemnity"
                                {{ $quote->policy_name == 'Professional Indemnity' ? 'selected' : '' }}>Professional
                                Indemnity Policy</option>
                            <option value="Cyber Insurance"
                                {{ $quote->policy_name == 'Cyber Insurance' ? 'selected' : '' }}>Cyber Insurance</option>
                            <option value="Political Violence Insurance"
                                {{ $quote->policy_name == 'Political Violence Insurance' ? 'selected' : '' }}>Political
                                Violence Insurance</option>
                        </select>
                    </div>
                    <div style="flex:1 1 400px;">
                        <label style="color:#2e3192; font-weight:600;">Policy Period</label>
                        @php
                            $period = $quote->policy_period ? explode(' - ', $quote->policy_period) : [null, null];
                            function parseDateForInput($dateStr)
                            {
                                if (!$dateStr) {
                                    return '';
                                }
                                try {
                                    return \Carbon\Carbon::createFromFormat('d/m/Y', $dateStr)->format('Y-m-d');
                                } catch (\Exception $e) {
                                    return '';
                                }
                            }
                        @endphp
                        <div style="display: flex; gap: 0.5rem;">
                            <input type="date" id="policy_start_date" name="policy_start_date" class="form-input"
                                value="{{ parseDateForInput($period[0] ?? '') }}" style="flex:1;">
                            <span style="align-self:center;">to</span>
                            <input type="date" id="policy_end_date" name="policy_end_date" class="form-input"
                                value="{{ parseDateForInput($period[1] ?? '') }}" style="flex:1;">
                        </div>
                        <div style="margin-top:0.5rem;">
                            <input type="checkbox" id="policy_period_tba" name="policy_period_tba" value="1"
                                onchange="togglePolicyPeriodTBA(this)">
                            <label for="policy_period_tba" style="color:#2e3192; font-weight:500; margin-left:4px;">To Be
                                Advised</label>
                        </div>
                    </div>
                </div>
                <div style="display: flex; flex-wrap: wrap; gap: 1.5rem; margin-top:1.2rem;">
                    <div style="flex:1 1 400px;">
                        <label for="currency" style="color:#2e3192; font-weight:600;">Currency Type</label>
                        <select id="currency" name="currency_type" class="form-input" onchange="updateCurrencyLabels()">
                            <option value="INR" {{ $quote->currency_type == 'INR' ? 'selected' : '' }}>INR</option>
                            <option value="USD" {{ $quote->currency_type == 'USD' ? 'selected' : '' }}>Dollar</option>
                            <option value="EUR" {{ $quote->currency_type == 'EUR' ? 'selected' : '' }}>Euro</option>
                        </select>
                    </div>
                    <div style="flex:1 1 400px;">
                        <label for="occupancy" style="color:#2e3192; font-weight:600;">Occupancy</label>
                        <input type="text" id="occupancy" name="occupancy" class="form-input"
                            value="{{ $quote->occupancy }}">
                    </div>
                    <div style="flex:1 1 400px;">
                        <label for="jurisdiction" style="color:#2e3192; font-weight:600;">Jurisdiction</label>
                        <input type="text" id="jurisdiction" name="jurisdiction" class="form-input"
                            value="{{ $quote->jurisdiction }}">
                    </div>
                </div>
                <!-- Risk Locations (multiple rows) -->
                <div style="margin-top:1.2rem;">
                    <label style="color:#2e3192; font-weight:600;">Risk Locations</label>
                    <div style="margin-bottom:0.7rem;">
                        <input type="checkbox" id="riskLocationAnnexure" name="risk_location_as_per_annexure" value="1"
                            onchange="toggleRiskLocationAnnexure()"
                            {{ $quote->risk_location_as_per_annexure == 1 ? 'checked' : '' }}>
                        <label for="riskLocationAnnexure" style="color:#2e3192; font-weight:500; margin-left:4px;">As per
                            Annexure</label>
                    </div>
                    <!-- Field Headers -->
                    <div style="display:flex; flex-wrap:wrap; gap:0.5rem; margin-bottom:0.5rem; padding:0.5rem; background:#e3f0ff; border-radius:6px; font-weight:600; color:#2e3192; font-size:0.9rem; {{ $quote->risk_location_as_per_annexure == 1 ? 'display:none;' : '' }}"
                        id="riskLocationHeaders">
                        <div style="flex:1 1 200px;">Risk Location</div>
                        <div style="flex:1 1 150px;">Property Damage</div>
                        <div style="flex:1 1 150px;">Business Interruption</div>
                        <div style="flex:1 1 150px;">Total Sum Insured</div>
                        <div style="width:40px;">Action</div>
                    </div>
                    <div id="riskLocationsWrapper"
                        style="{{ $quote->risk_location_as_per_annexure == 1 ? 'display:none;' : '' }}">
                        @php
                            $riskLocations = $quote->risk_locations ? json_decode($quote->risk_locations, true) : [];
                        @endphp
                        @if ($quote->risk_location_as_per_annexure == 0)
                            @if (is_array($riskLocations) && count($riskLocations) > 0)
                                @foreach ($riskLocations as $location)
                                    @if (isset($location['location']))
                                        <div
                                            style="display:flex; flex-wrap:wrap; gap:0.5rem; margin-bottom:1rem; padding:1rem; border:1px solid #e3f0ff; border-radius:8px; background:#f7faff;">
                                            <input type="text" name="risk_location[]" class="form-input"
                                                style="flex:1 1 200px;" value="{{ $location['location'] ?? '' }}"
                                                placeholder="Enter risk location">
                                            <input type="number" name="risk_property_damage[]" class="form-input"
                                                style="flex:1 1 150px;" value="{{ $location['property_damage'] ?? 0 }}"
                                                placeholder="Property Damage" min="0" step="any"
                                                oninput="calculateRiskTotal(this)">
                                            <input type="number" name="risk_business_interruption[]" class="form-input"
                                                style="flex:1 1 150px;"
                                                value="{{ $location['business_interruption'] ?? 0 }}"
                                                placeholder="Business Interruption" min="0" step="any"
                                                oninput="calculateRiskTotal(this)">
                                            <input type="number" name="risk_total_sum[]" class="form-input"
                                                style="flex:1 1 150px;" value="{{ $location['total_sum_insured'] ?? 0 }}"
                                                placeholder="Total Sum Insured" readonly style="background:#e3f0ff;">
                                            <button type="button" onclick="removeRiskLocation(this)"
                                                style="background:#e74c3c; color:#fff; border:none; border-radius:6px; padding:0.3rem 0.8rem; font-size:1rem; cursor:pointer;">&times;</button>
                                        </div>
                                    @endif
                                @endforeach
                            @else
                                <!-- Default empty row if no data exists -->
                                <div
                                    style="display:flex; flex-wrap:wrap; gap:0.5rem; margin-bottom:1rem; padding:1rem; border:1px solid #e3f0ff; border-radius:8px; background:#f7faff;">
                                    <input type="text" name="risk_location[]" class="form-input"
                                        style="flex:1 1 200px;" placeholder="Enter risk location">
                                    <input type="number" name="risk_property_damage[]" class="form-input"
                                        style="flex:1 1 150px;" placeholder="Property Damage" min="0"
                                        step="any" oninput="calculateRiskTotal(this)">
                                    <input type="number" name="risk_business_interruption[]" class="form-input"
                                        style="flex:1 1 150px;" placeholder="Business Interruption" min="0"
                                        step="any" oninput="calculateRiskTotal(this)">
                                    <input type="number" name="risk_total_sum[]" class="form-input"
                                        style="flex:1 1 150px;" placeholder="Total Sum Insured" readonly
                                        style="background:#e3f0ff;">
                                    <button type="button" onclick="removeRiskLocation(this)"
                                        style="background:#e74c3c; color:#fff; border:none; border-radius:6px; padding:0.3rem 0.8rem; font-size:1rem; cursor:pointer;">&times;</button>
                                </div>
                            @endif
                        @endif
                    </div>
                    <button type="button" onclick="addRiskLocation()"
                        style="margin-top:0.5rem; background:#2e3192; color:#fff; border:none; border-radius:6px; padding:0.4rem 1.2rem; font-size:0.95rem; cursor:pointer;">Add
                        More</button>
                    <input type="hidden" id="riskLocationsJson" name="risk_locations_json">
                    <input type="hidden" id="riskLocationAnnexureHidden" name="risk_location_as_per_annexure"
                        value="{{ $quote->risk_location_as_per_annexure }}">
                </div>

                <!-- Sum Insured Details -->
                <div style="display: flex; flex-wrap: wrap; gap: 1.5rem; margin-top:1.2rem;">
                    <div style="flex:1 1 300px;">
                        <label for="property_damage" style="color:#2e3192; font-weight:600;">Property Damage (<span
                                class="currency-label">{{ $quote->currency_type ?? 'INR' }}</span>)</label>
                        <input type="number" id="property_damage" name="property_damage" class="form-input"
                            min="0" step="any" value="{{ $quote->property_damage }}"
                            oninput="updateTotalSumInsured()"
                            placeholder="Enter amount in {{ $quote->currency_type ?? 'INR' }}">
                    </div>
                    <div style="flex:1 1 300px;">
                        <label for="business_interruption" style="color:#2e3192; font-weight:600;">Business Interruption
                            (<span class="currency-label">{{ $quote->currency_type ?? 'INR' }}</span>)</label>
                        <input type="number" id="business_interruption" name="business_interruption" class="form-input"
                            min="0" step="any" value="{{ $quote->business_interruption }}"
                            oninput="updateTotalSumInsured()"
                            placeholder="Enter amount in {{ $quote->currency_type ?? 'INR' }}">
                    </div>
                    <div style="flex:1 1 300px;">
                        <label for="total_sum_insured" style="color:#2e3192; font-weight:600;">Total Sum Insured (<span
                                class="currency-label">{{ $quote->currency_type ?? 'INR' }}</span>)</label>
                        <input type="number" id="total_sum_insured" name="total_sum_insured" class="form-input"
                            value="{{ $quote->total_sum_insured }}" readonly style="background:#e3f0ff;"
                            placeholder="Amount in {{ $quote->currency_type ?? 'INR' }}">
                    </div>
                </div>
                <div style="margin-top:1.2rem;">
                    <label for="coverage" style="color:#2e3192; font-weight:600;">Coverage</label>
                    <input type="text" id="coverage" name="coverage" class="form-input"
                        value="{{ $quote->coverage }}">
                </div>
                <!-- Limit of Indemnity (multiple rows) -->
                <div style="margin-top:1.2rem;">
                    <label style="color:#2e3192; font-weight:600;">Limit of Indemnity</label>
                    <div id="indemnityLimitsWrapper">
                        @php
                            $limits = $quote->limit_of_indemnity ? json_decode($quote->limit_of_indemnity, true) : [''];
                        @endphp
                        @foreach ($limits as $lim)
                            <input type="text" name="limit_of_indemnity[]" class="form-input"
                                value="{{ $lim }}" placeholder="Enter limit of indemnity">
                        @endforeach
                    </div>
                    <button type="button" onclick="addIndemnityLimit()"
                        style="margin-top:0.5rem; background:#2e3192; color:#fff; border:none; border-radius:6px; padding:0.4rem 1.2rem; font-size:0.95rem; cursor:pointer;">Add
                        More</button>
                </div>
                <div style="display: flex; flex-wrap: wrap; gap: 1.5rem; margin-top:1.2rem;">
                    <div style="flex:1 1 400px;">
                        <label for="indemnity_period" style="color:#2e3192; font-weight:600;">Indemnity Period (Business
                            Interruption)</label>
                        <input type="text" id="indemnity_period" name="indemnity_period" class="form-input"
                            value="{{ $quote->indemnity_period }}">
                    </div>
                    <div style="flex:1 1 400px;">
                        <label style="color:#2e3192; font-weight:600;">Additional Covers Opted</label>
                        <div id="additionalCoversWrapper">
                            @php
                                $additionalCovers = $quote->additional_covers
                                    ? json_decode($quote->additional_covers, true)
                                    : [''];
                            @endphp
                            @foreach ($additionalCovers as $cover)
                                <input type="text" name="additional_covers[]" class="form-input"
                                    value="{{ $cover }}" placeholder="Enter additional cover">
                            @endforeach
                        </div>
                        <button type="button" onclick="addAdditionalCover()"
                            style="margin-top:0.5rem; background:#2e3192; color:#fff; border:none; border-radius:6px; padding:0.4rem 1.2rem; font-size:0.95rem; cursor:pointer;">
                            Add More
                        </button>
                    </div>
                </div>
                <div style="margin-top:1.2rem;">
                    <label for="claims" style="color:#2e3192; font-weight:600;">Claims</label>
                    <textarea id="claims" name="claims" class="form-input" rows="2">{{ $quote->claims }}</textarea>
                </div>
                <div style="display: flex; flex-wrap: wrap; gap: 1.5rem; margin-top:1.2rem;">
                    <div style="flex:1 1 400px;">
                        <label style="color:#2e3192; font-weight:600;">Deductibles</label>
                        <div id="deductiblesWrapper">
                            @php
                                $deductibles = $quote->deductibles ? json_decode($quote->deductibles, true) : [''];
                            @endphp
                            @foreach ($deductibles as $deductible)
                                <input type="text" name="deductibles[]" class="form-input"
                                    value="{{ $deductible }}" placeholder="Enter deductible">
                            @endforeach
                        </div>
                        <button type="button" onclick="addDeductible()"
                            style="margin-top:0.5rem; background:#2e3192; color:#fff; border:none; border-radius:6px; padding:0.4rem 1.2rem; font-size:0.95rem; cursor:pointer;">
                            Add More
                        </button>
                    </div>
                    <div style="flex:1 1 400px;">
                        <label for="premium" style="color:#2e3192; font-weight:600;">Premium</label>
                        <input type="text" id="premium" name="premium" class="form-input"
                            value="{{ $quote->premium }}">
                    </div>
                </div>
                <div style="margin-top:1.2rem;">
                    <label for="support" style="color:#2e3192; font-weight:600;">Support</label>
                    <input type="text" id="support" name="support" class="form-input"
                        value="{{ $quote->support }}">
                </div>
                <!-- Remark Fields -->
                <div style="margin-top:1.5rem;">
                    <div style="display: flex; flex-wrap: wrap; gap: 1.5rem;">
                        <div style="flex:1 1 300px;">
                            <label for="remark1" style="color:#2e3192; font-weight:600;">Remark 1</label>
                            <textarea id="remark1" name="remark1" class="form-input" rows="2">{{ $quote->remark1 }}</textarea>
                        </div>
                        <div style="flex:1 1 300px;">
                            <label for="remark2" style="color:#2e3192; font-weight:600;">Remark 2</label>
                            <textarea id="remark2" name="remark2" class="form-input" rows="2">{{ $quote->remark2 }}</textarea>
                        </div>
                        <div style="flex:1 1 300px;">
                            <label for="remark3" style="color:#2e3192; font-weight:600;">Remark 3</label>
                            <textarea id="remark3" name="remark3" class="form-input" rows="2">{{ $quote->remark3 }}</textarea>
                        </div>
                    </div>
                </div>
                <div style="margin-top:1.5rem;">
                    <div style="display: flex; flex-wrap: wrap; gap: 1.5rem;">
                        <div style="flex:1 1 300px;">
                            <label for="is_final_submit" style="color:#2e3192; font-weight:600;">Is Converted?</label>
                            <select id="is_final_submit" name="is_final_submit" class="form-input">
                                <option value="0">No</option>
                                <option value="1">Yes</option>
                                <option value="2">Lost</option>

                            </select>
                            <div id="whyLostField" style="display:none; margin-top:1rem;">
                                <label for="why_lost" style="color:#e74c3c; font-weight:600;">Why Lost?</label>
                                <input type="text" id="why_lost" name="bussiness_lost" class="form-input"
                                    placeholder="Please specify why lost...">
                            </div>
                        </div>
                    </div>

                    <!-- Hidden Fields (Sedant & Reinsurer) -->
                    <div id="conversionFields" style="display:none; margin-top:1rem; flex-wrap: wrap; gap: 1.5rem;">
                        <div style="flex:1 1 300px;">
                            <label for="cedant_name" style="color:#2e3192; font-weight:600;">Cedant Name</label>
                            <input type="text" name="cedant" id="cedant_name" class="form-input"
                                placeholder="Enter Cedant Name" value="{{ $quote->cedant ?? '' }}">
                        </div>

                        <div style="flex:1 1 300px;">
                            <label for="brokerage_percentage" style="color:#2e3192; font-weight:600;">Our Brokerage
                                Percentage</label>
                            <input type="number" name="brokerage_percentage" id="brokerage_percentage"
                                class="form-input" placeholder="100" value="{{ $quote->brokerage_percentage ?? 100 }}"
                                min="0" max="100" step="0.01">
                        </div>

                        <div style="flex:1 1 300px;">
                            <label for="reinsurer_country" style="color:#2e3192; font-weight:600;">Country of
                                ReInsurer</label>
                            <select name="reinsurer_country" id="reinsurer_country" class="form-input">
                                <option value="">Select Country</option>
                                <option value="India"
                                    {{ ($quote->reinsurer_country ?? '') == 'India' ? 'selected' : '' }}>India</option>
                                <option value="India- GIFT City, Gujarat"
                                    {{ ($quote->reinsurer_country ?? '') == 'India- GIFT City, Gujarat' ? 'selected' : '' }}>
                                    India- GIFT City, Gujarat</option>
                                <option value="Thailand"
                                    {{ ($quote->reinsurer_country ?? '') == 'Thailand' ? 'selected' : '' }}>Thailand
                                </option>
                                <option value="Hongkong"
                                    {{ ($quote->reinsurer_country ?? '') == 'Hongkong' ? 'selected' : '' }}>Hongkong
                                </option>
                                <option value="Singapore"
                                    {{ ($quote->reinsurer_country ?? '') == 'Singapore' ? 'selected' : '' }}>Singapore
                                </option>
                                <option value="UAE" {{ ($quote->reinsurer_country ?? '') == 'UAE' ? 'selected' : '' }}>
                                    UAE</option>
                                <option value="Malaysia"
                                    {{ ($quote->reinsurer_country ?? '') == 'Malaysia' ? 'selected' : '' }}>Malaysia
                                </option>
                                <option value="Germany"
                                    {{ ($quote->reinsurer_country ?? '') == 'Germany' ? 'selected' : '' }}>Germany</option>
                                <option value="UK" {{ ($quote->reinsurer_country ?? '') == 'UK' ? 'selected' : '' }}>
                                    UK</option>
                            </select>
                        </div>
                        <div style="flex:1 1 100%; margin-top:1rem;">
                            <label style="color:#2e3192; font-weight:600;">Reinsurer Names & Business Share</label>
                            <div id="reinsurersWrapper">
                                @php
                                    $reinsurers = $quote->reinsurers
                                        ? json_decode($quote->reinsurers, true)
                                        : [['name' => '', 'percentage' => '', 'brokerage' => '']];
                                @endphp
                                @foreach ($reinsurers as $reinsurer)
                                    <div style="display:flex; gap:0.5rem; margin-bottom:0.5rem; align-items:center;">
                                        <input type="text" name="reinsurer_names[]" class="form-input"
                                            value="{{ is_array($reinsurer) ? $reinsurer['name'] : $reinsurer }}"
                                            placeholder="Enter Reinsurer Name" style="flex:2;">
                                        <input type="number" name="reinsurer_percentages[]" class="form-input"
                                            value="{{ is_array($reinsurer) ? $reinsurer['percentage'] : '' }}"
                                            placeholder="Share %" min="0" max="100" step="0.01"
                                            style="flex:1;" oninput="calculateTotal()">
                                        <span style="color:#2e3192; font-weight:500; font-size:0.9rem;">%</span>
                                        <input type="number" name="reinsurer_ceding_commissions[]" class="form-input"
                                            value="{{ is_array($reinsurer) ? ($reinsurer['ceding_commission'] ?? '') : '' }}"
                                            placeholder="Ceding Commission %" min="0" max="100" step="0.01"
                                            style="flex:1;">
                                        <span style="color:#2e3192; font-weight:500; font-size:0.9rem;">%</span>
                                        <input type="number" name="reinsurer_brokerages[]" class="form-input"
                                            value="{{ is_array($reinsurer) ? ($reinsurer['brokerage'] ?? '') : '' }}"
                                            placeholder="Our Brokerage %" min="0" max="100" step="0.01"
                                            style="flex:1;">
                                        <span style="color:#2e3192; font-weight:500; font-size:0.9rem;">%</span>
                                        <button type="button" onclick="removeReinsurer(this)"
                                            style="background:#e74c3c; color:#fff; border:none; border-radius:6px; padding:0.3rem 0.8rem; font-size:1rem; cursor:pointer;">&times;</button>
                                    </div>
                                @endforeach
                            </div>
                            <div style="margin-top:0.5rem; display:flex; gap:1rem; align-items:center;">
                                <button type="button" onclick="addReinsurer()"
                                    style="background:#2e3192; color:#fff; border:none; border-radius:6px; padding:0.4rem 1.2rem; font-size:0.95rem; cursor:pointer;">
                                    Add More Reinsurer
                                </button>
                                <div id="totalPercentage" style="color:#2e3192; font-weight:600; font-size:0.95rem;">
                                    Total: <span id="totalPercent">0</span>%
                                </div>
                            </div>
                            <input type="hidden" name="reinsurers_json" id="reinsurersJson">
                        </div>


                        <script>
                            // Add more reinsurers with percentage and brokerage
                            function addReinsurer() {
                                const wrapper = document.getElementById('reinsurersWrapper');
                                const div = document.createElement('div');
                                div.style.display = 'flex';
                                div.style.gap = '0.5rem';
                                div.style.marginBottom = '0.5rem';
                                div.style.alignItems = 'center';
                                div.innerHTML = `
        <input type="text" name="reinsurer_names[]" class="form-input" placeholder="Enter Reinsurer Name" style="flex:2;">
        <input type="number" name="reinsurer_percentages[]" class="form-input" placeholder="Share %" min="0" max="100" step="0.01" style="flex:1;" oninput="calculateTotal()">
        <span style="color:#2e3192; font-weight:500; font-size:0.9rem;">%</span>
        <input type="number" name="reinsurer_ceding_commissions[]" class="form-input" placeholder="Ceding Commission %" min="0" max="100" step="0.01" style="flex:1;">
        <span style="color:#2e3192; font-weight:500; font-size:0.9rem;">%</span>
        <input type="number" name="reinsurer_brokerages[]" class="form-input" placeholder="Our Brokerage %" min="0" max="100" step="0.01" style="flex:1;">
        <span style="color:#2e3192; font-weight:500; font-size:0.9rem;">%</span>
        <button type="button" onclick="removeReinsurer(this)" style="background:#e74c3c; color:#fff; border:none; border-radius:6px; padding:0.3rem 0.8rem; font-size:1rem; cursor:pointer;">&times;</button>
    `;
                                wrapper.appendChild(div);
                                calculateTotal();
                            }

                            function removeReinsurer(btn) {
                                btn.parentElement.remove();
                                calculateTotal();
                            }

                            // Calculate total percentage
                            function calculateTotal() {
                                const percentages = document.getElementsByName('reinsurer_percentages[]');
                                let total = 0;
                                for (let i = 0; i < percentages.length; i++) {
                                    const value = parseFloat(percentages[i].value) || 0;
                                    total += value;
                                }
                                document.getElementById('totalPercent').textContent = total.toFixed(2);

                                // Color coding for total
                                const totalElement = document.getElementById('totalPercentage');
                                if (total > 100) {
                                    totalElement.style.color = '#e74c3c'; // Red if over 100%
                                } else if (total === 100) {
                                    totalElement.style.color = '#27ae60'; // Green if exactly 100%
                                } else {
                                    totalElement.style.color = '#2e3192'; // Blue if under 100%
                                }
                            }

                            // Add event listeners to existing percentage inputs
                            document.addEventListener('DOMContentLoaded', function() {
                                // Add event listeners to existing inputs
                                const existingPercentages = document.getElementsByName('reinsurer_percentages[]');
                                for (let i = 0; i < existingPercentages.length; i++) {
                                    existingPercentages[i].addEventListener('input', calculateTotal);
                                }

                                // Calculate initial total
                                calculateTotal();

                                // Prepare JSON on form submit
                                document.querySelector('form').addEventListener('submit', function(e) {
                                    const names = document.getElementsByName('reinsurer_names[]');
                                    const percentages = document.getElementsByName('reinsurer_percentages[]');
                                    const cedings = document.getElementsByName('reinsurer_ceding_commissions[]');
                                    const brokerages = document.getElementsByName('reinsurer_brokerages[]');
                                    let reinsurersArray = [];

                                    for (let i = 0; i < names.length; i++) {
                                        const name = names[i].value.trim();
                                        const percentage = parseFloat(percentages[i].value) || 0;
                                        const ceding = parseFloat((cedings[i] && cedings[i].value) || 0) || 0;
                                        const brokerage = parseFloat((brokerages[i] && brokerages[i].value) || 0) || 0;
                                        if (name !== '') {
                                            reinsurersArray.push({
                                                name: name,
                                                percentage: percentage,
                                                ceding_commission: ceding,
                                                brokerage: brokerage
                                            });
                                        }
                                    }

                                    document.getElementById('reinsurersJson').value = JSON.stringify(reinsurersArray);
                                });
                            });

                            // ...existing JavaScript functions...
                        </script>
                    </div>

                </div>
                <div style="text-align:center; margin-top:2rem;">
                    <button type="submit"
                        style="background: linear-gradient(90deg, #2e3192 0%, #1bffff 100%); color: #fff; border: none; border-radius: 8px; padding: 0.9rem 2.5rem; font-size: 1.1rem; font-weight: 700; cursor: pointer; box-shadow: 0 2px 8px #2e319222; transition: background 0.2s;">
                        <i class="fas fa-save"></i> Update Quote
                    </button>
                </div>
            </form>
        </div>
    </div>
    <style>
        .form-input {
            width: 100%;
            padding: 0.7rem 1rem;
            border: 1.5px solid #e3f0ff;
            border-radius: 8px;
            background: #f7faff;
            font-size: 1rem;
            margin-top: 0.3rem;
            transition: border 0.2s;
            box-sizing: border-box;
        }

        .form-input:focus {
            border-color: #2e3192;
            outline: none;
            background: #e3f0ff;
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const select = document.getElementById('is_final_submit');
            const fields = document.getElementById('conversionFields');
            const whyLost = document.getElementById('whyLostField');

            function toggleFields() {
                if (select.value === "1") {
                    fields.style.display = "flex";
                    whyLost.style.display = "none";
                } else if (select.value === "2") {
                    fields.style.display = "none";
                    whyLost.style.display = "block";
                } else {
                    fields.style.display = "none";
                    whyLost.style.display = "none";
                }
            }

            // Run on page load (in case of edit)
            toggleFields();

            // Run on change
            select.addEventListener('change', toggleFields);
        });
    </script>

    <script>
        // Add more indemnity limits
        function addIndemnityLimit() {
            const wrapper = document.getElementById('indemnityLimitsWrapper');
            const input = document.createElement('input');
            input.type = 'text';
            input.name = 'limit_of_indemnity[]';
            input.className = 'form-input';
            input.placeholder = 'Enter limit of indemnity';
            input.style.marginTop = '0.5rem';
            wrapper.appendChild(input);
        }
        // Add more additional covers
        function addAdditionalCover() {
            const wrapper = document.getElementById('additionalCoversWrapper');
            const input = document.createElement('input');
            input.type = 'text';
            input.name = 'additional_covers[]';
            input.className = 'form-input';
            input.placeholder = 'Enter additional cover';
            input.style.marginTop = '0.5rem';
            wrapper.appendChild(input);
        }
        // Add more deductibles
        function addDeductible() {
            const wrapper = document.getElementById('deductiblesWrapper');
            const input = document.createElement('input');
            input.type = 'text';
            input.name = 'deductibles[]';
            input.className = 'form-input';
            input.placeholder = 'Enter deductible';
            input.style.marginTop = '0.5rem';
            wrapper.appendChild(input);
        }
        // Update total sum insured
        function updateTotalSumInsured() {
            const pd = parseFloat(document.getElementById('property_damage').value) || 0;
            const bi = parseFloat(document.getElementById('business_interruption').value) || 0;
            document.getElementById('total_sum_insured').value = pd + bi;
        }
    </script>
    <script>
        // Add more reinsurers

        // Show/hide conversion fields
        document.addEventListener('DOMContentLoaded', function() {
            const isConvertedSelect = document.getElementById('is_final_submit');
            const conversionFields = document.getElementById('conversionFields');

            isConvertedSelect.addEventListener('change', function() {
                if (this.value == '1') {
                    conversionFields.style.display = 'flex';
                } else {
                    conversionFields.style.display = 'none';
                }
            });

            // Set initial state
            if (isConvertedSelect.value == '1') {
                conversionFields.style.display = 'flex';
            }
        });

        // ...existing JavaScript functions...
    </script>
    <script>
        // Download sample CSV
        function downloadSampleCSV() {
            const csv =
                "Risk Location,Property Damage,Business Interruption,Total Sum Insured\nLocation 1,800000,200000,1000000\nLocation 2,1500000,500000,2000000";
            const blob = new Blob([csv], {
                type: 'text/csv'
            });
            const url = window.URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = "risk_locations_sample.csv";
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);
            window.URL.revokeObjectURL(url);
        }

        // Handle CSV upload
        function handleCSVUpload(input) {
            const file = input.files[0];
            if (!file) return;
            const reader = new FileReader();
            reader.onload = function(e) {
                const lines = e.target.result.split('\n').map(l => l.trim()).filter(l => l.length);
                // Remove header if present
                let start = 0;
                if (lines[0].toLowerCase().includes('risk location')) start = 1;
                // Remove all current fields except the first one
                const wrapper = document.getElementById('riskLocationsWrapper');
                wrapper.innerHTML = '';
                for (let i = start; i < lines.length; i++) {
                    const parts = lines[i].split(',');
                    if (parts.length >= 3) {
                        const div = document.createElement('div');
                        div.style.display = 'flex';
                        div.style.flexWrap = 'wrap';
                        div.style.gap = '0.5rem';
                        div.style.marginBottom = '1rem';
                        div.style.padding = '1rem';
                        div.style.border = '1px solid #e3f0ff';
                        div.style.borderRadius = '8px';
                        div.style.background = '#f7faff';
                        const propertyDamage = parseFloat(parts[1].trim()) || 0;
                        const businessInterruption = parseFloat(parts[2].trim()) || 0;
                        const total = propertyDamage + businessInterruption;
                        div.innerHTML = `
                    <input type="text" name="risk_location[]" class="form-input" style="flex:1 1 200px;" placeholder="Enter risk location" value="${parts[0].trim()}" required>
                    <input type="number" name="risk_property_damage[]" class="form-input" style="flex:1 1 150px;" placeholder="Property Damage" min="0" step="any" value="${propertyDamage}" oninput="calculateRiskTotal(this)" required>
                    <input type="number" name="risk_business_interruption[]" class="form-input" style="flex:1 1 150px;" placeholder="Business Interruption" min="0" step="any" value="${businessInterruption}" oninput="calculateRiskTotal(this)" required>
                    <input type="number" name="risk_total_sum[]" class="form-input" style="flex:1 1 150px;" placeholder="Total Sum Insured" value="${total}" readonly style="background:#e3f0ff;">
                    <button type="button" onclick="removeRiskLocation(this)" style="background:#e74c3c; color:#fff; border:none; border-radius:6px; padding:0.3rem 0.8rem; font-size:1rem; cursor:pointer;">&times;</button>
                `;
                        wrapper.appendChild(div);
                    }
                }
            };
            reader.readAsText(file);
        }
    </script>
    <script>
        function addRiskLocation() {
            const wrapper = document.getElementById('riskLocationsWrapper');
            const div = document.createElement('div');
            div.style.display = 'flex';
            div.style.flexWrap = 'wrap';
            div.style.gap = '0.5rem';
            div.style.marginBottom = '1rem';
            div.style.padding = '1rem';
            div.style.border = '1px solid #e3f0ff';
            div.style.borderRadius = '8px';
            div.style.background = '#f7faff';
            div.innerHTML = `
        <input type="text" name="risk_location[]" class="form-input" style="flex:1 1 200px;" placeholder="Enter risk location" required>
        <input type="number" name="risk_property_damage[]" class="form-input" style="flex:1 1 150px;" placeholder="Property Damage" min="0" step="any" oninput="calculateRiskTotal(this)" required>
        <input type="number" name="risk_business_interruption[]" class="form-input" style="flex:1 1 150px;" placeholder="Business Interruption" min="0" step="any" oninput="calculateRiskTotal(this)" required>
        <input type="number" name="risk_total_sum[]" class="form-input" style="flex:1 1 150px;" placeholder="Total Sum Insured" readonly style="background:#e3f0ff;">
        <button type="button" onclick="removeRiskLocation(this)" style="background:#e74c3c; color:#fff; border:none; border-radius:6px; padding:0.3rem 0.8rem; font-size:1rem; cursor:pointer;">&times;</button>
    `;
            wrapper.appendChild(div);
        }

        function removeRiskLocation(btn) {
            btn.parentElement.remove();
        }

        // Calculate total sum for each risk location
        function calculateRiskTotal(input) {
            const container = input.closest('div');
            const propertyDamage = parseFloat(container.querySelector('input[name="risk_property_damage[]"]').value) || 0;
            const businessInterruption = parseFloat(container.querySelector('input[name="risk_business_interruption[]"]')
                .value) || 0;
            const totalField = container.querySelector('input[name="risk_total_sum[]"]');
            totalField.value = propertyDamage + businessInterruption;
        }

        // On form submit, build JSON and put in hidden
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelector('form').addEventListener('submit', function(e) {
                const tba = document.getElementById('policy_period_tba');
                if (tba && tba.checked) {
                    document.getElementById('policy_start_date').value = '';
                    document.getElementById('policy_end_date').value = '';
                }

                const locations = document.getElementsByName('risk_location[]');
                const propertyDamages = document.getElementsByName('risk_property_damage[]');
                const businessInterruptions = document.getElementsByName('risk_business_interruption[]');
                const totalSums = document.getElementsByName('risk_total_sum[]');
                const hidden = document.getElementById('riskLocationsJson');

                let arr = [];

                // Annexure logic
                var annexureCheckbox = document.getElementById('riskLocationAnnexure');
                var wrapper = document.getElementById('riskLocationsWrapper');
                var hiddenAnnexure = document.getElementById('riskLocationAnnexureHidden');
                if (annexureCheckbox && annexureCheckbox.checked) {
                    // Hide risk location fields and send null
                    locations.forEach(function(input) {
                        input.value = '';
                        input.removeAttribute('required');
                    });
                    propertyDamages.forEach(function(input) {
                        input.value = '';
                        input.removeAttribute('required');
                    });
                    businessInterruptions.forEach(function(input) {
                        input.value = '';
                        input.removeAttribute('required');
                    });
                    totalSums.forEach(function(input) {
                        input.value = '';
                    });
                    hiddenAnnexure.value = '1';
                    if (hidden) hidden.value = '';
                    // Also send null for all risk location fields
                    // Remove all risk location fields from form submission
                    locations.forEach(function(input) {
                        input.disabled = true;
                    });
                    propertyDamages.forEach(function(input) {
                        input.disabled = true;
                    });
                    businessInterruptions.forEach(function(input) {
                        input.disabled = true;
                    });
                    totalSums.forEach(function(input) {
                        input.disabled = true;
                    });
                } else {
                    locations.forEach(function(input) {
                        input.disabled = false;
                    });
                    propertyDamages.forEach(function(input) {
                        input.disabled = false;
                    });
                    businessInterruptions.forEach(function(input) {
                        input.disabled = false;
                    });
                    totalSums.forEach(function(input) {
                        input.disabled = false;
                    });
                    for (let i = 0; i < locations.length; i++) {
                        const loc = locations[i].value.trim();
                        const propDamage = parseFloat(propertyDamages[i].value) || 0;
                        const bizInterruption = parseFloat(businessInterruptions[i].value) || 0;
                        const total = parseFloat(totalSums[i].value) || 0;

                        if (loc !== '') {
                            arr.push({
                                location: loc,
                                property_damage: propDamage,
                                business_interruption: bizInterruption,
                                total_sum_insured: total
                            });
                        }
                    }
                    if (hidden) hidden.value = arr.length ? JSON.stringify(arr) : '';
                    hiddenAnnexure.value = '0';
                }
            });
        });
    </script>
    <script>
        function toggleRiskLocationAnnexure() {
            var annexureCheckbox = document.getElementById('riskLocationAnnexure');
            var wrapper = document.getElementById('riskLocationsWrapper');
            var headers = document.getElementById('riskLocationHeaders');
            var hidden = document.getElementById('riskLocationAnnexureHidden');
            if (annexureCheckbox.checked) {
                wrapper.style.display = 'none';
                headers.style.display = 'none';
                hidden.value = '1';
                // Clear all risk location fields
                var inputs = wrapper.querySelectorAll('input');
                inputs.forEach(function(input) {
                    input.value = '';
                });
            } else {
                wrapper.style.display = '';
                headers.style.display = 'flex';
                hidden.value = '0';
            }
        }
    </script>
@endsection
