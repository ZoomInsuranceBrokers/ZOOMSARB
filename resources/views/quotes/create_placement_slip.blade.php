@extends('layouts.app')

@section('title', 'Placement Slip')

@section('content')
<div class="container py-5" style="display: flex; justify-content: center; align-items: center; min-height: 80vh;">
    <div class="card shadow-lg" style="width: 95%; max-width: 1400px; margin: 0 auto;">
        <div class="card-header bg-primary text-white text-center">
            <h3 class="mb-0">Create Placement Slip</h3>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('placement-slip.store') }}">
                @csrf
                <input type="hidden" name="quote_id" value="{{ $quote->id }}">

                <!-- Quote Information Fields -->
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
                            <div style="display:flex; gap:0.5rem; margin-bottom:0.5rem; align-items:center;">
                                <input type="text" name="limit_of_indemnity[]" class="form-input"
                                    value="{{ $lim }}" placeholder="Enter limit of indemnity" style="flex:1;">
                                <button type="button" onclick="removeIndemnityLimit(this)"
                                    style="background:#e74c3c; color:#fff; border:none; border-radius:6px; padding:0.3rem 0.8rem; font-size:1rem; cursor:pointer;">&times;</button>
                            </div>
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
                                <div style="display:flex; gap:0.5rem; margin-bottom:0.5rem; align-items:center;">
                                    <input type="text" name="additional_covers[]" class="form-input"
                                        value="{{ $cover }}" placeholder="Enter additional cover" style="flex:1;">
                                    <button type="button" onclick="removeAdditionalCover(this)"
                                        style="background:#e74c3c; color:#fff; border:none; border-radius:6px; padding:0.3rem 0.8rem; font-size:1rem; cursor:pointer;">&times;</button>
                                </div>
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
                                <div style="display:flex; gap:0.5rem; margin-bottom:0.5rem; align-items:center;">
                                    <input type="text" name="deductibles[]" class="form-input"
                                        value="{{ $deductible }}" placeholder="Enter deductible" style="flex:1;">
                                    <button type="button" onclick="removeDeductible(this)"
                                        style="background:#e74c3c; color:#fff; border:none; border-radius:6px; padding:0.3rem 0.8rem; font-size:1rem; cursor:pointer;">&times;</button>
                                </div>
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

                <!-- Placement Slip Specific Fields -->
                <hr style="margin: 2rem 0; border-color: #e3f0ff;">
                <h5 style="color:#2e3192; font-weight:700; margin-bottom:1.5rem;">Placement Slip Details</h5>

                <!-- PPW / PPC Radio Buttons -->
                <div style="margin-bottom:1.5rem;">
                    <label style="color:#2e3192; font-weight:600;">Select Type</label>
                    <div style="margin-top:0.5rem;">
                        <input type="radio" id="ppw" name="placement_type" value="PPW" checked onchange="togglePlacementTypeField()">
                        <label for="ppw" style="color:#2e3192; font-weight:500; margin-left:4px; margin-right:1.5rem;">PPW </label>
                        
                        <input type="radio" id="ppc" name="placement_type" value="PPC" onchange="togglePlacementTypeField()">
                        <label for="ppc" style="color:#2e3192; font-weight:500; margin-left:4px;">PPC </label>
                    </div>
                    
                    <!-- PPW Input Field -->
                    <div id="ppwField" style="margin-top:1rem;">
                        <label for="ppw_input" style="color:#2e3192; font-weight:600;">PPW Details</label>
                        <textarea id="ppw_input" name="ppw_details" class="form-input" rows="4" placeholder="Enter PPW details..."></textarea>
                    </div>
                    
                    <!-- PPC Input Field -->
                    <div id="ppcField" style="margin-top:1rem; display:none;">
                        <label for="ppc_input" style="color:#2e3192; font-weight:600;">PPC Details</label>
                        <textarea id="ppc_input" name="ppc_details" class="form-input" rows="4" placeholder="Enter PPC details..."></textarea>
                    </div>
                </div>

                <div class="mb-4">
                    <label for="reinsurer_name" class="form-label fw-bold">Reinsurer Name</label>
                    <select id="reinsurer_name" name="reinsurer_name" class="form-select" required>
                        <option value="">Select Reinsurer</option>
                        @php
                            $reinsurers = $quote->reinsurer ? json_decode($quote->reinsurer, true) : [];
                        @endphp
                        @foreach ($reinsurers as $reinsurer)
                            <option value="{{ is_array($reinsurer) ? $reinsurer['name'] : $reinsurer }}">
                                {{ is_array($reinsurer) ? $reinsurer['name'] : $reinsurer }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-4">
                    <label for="to" class="form-label fw-bold">To</label>
                    <select id="to" name="to" class="form-select" required>
                        <option value="">Select Company</option>
                        <option
                            value="Go Digit General Insurance Limited&#x0A;Ananta One ,1st floor, Opposite Shivaji Nagar Metro Stand ,Near Dalvi Hospital, Narveer Tanaji Wadi, Shivajinagar,Pune, Maharashtra 411005">
                            Go Digit General Insurance Limited</option>
                        <option value="Everest&#x0A;30 Raffles Place Singapore 048622">Everest</option>
                        <option value="Echo Re&#x0A;Echo Reinsurance Limited Brandschenkestrasse 18-208001 Zurich">Echo Re</option>
                        <option value="GP / CelsiusPro&#x0A;Seebahnstrasse 85 CH‑8003 Zürich Switzerland">GP / CelsiusPro</option>
                        <option value="Canopius&#x0A;138 Market Street CapitaGreen, #04-01 Singapore 048946">Canopius</option>
                        <option value="Axa Climate&#x0A;AXA XL, A division of AXA Unit No. 608, 6th Floor, INS Tower, A Wing, Plot No. C-63, ‘G’ Block, Bandra Kurla Complex, Mumbai, India – 400051">Axa Climate</option>
                        <option value="SCOR&#x0A;Unit 907, 908–910, Kanakia Wallstreet, Village Chakala and Mulgaon, Andheri Kurla Road, Andheri East, Mumbai – 400093, India">SCOR</option>
                        <option value="Peak Re&#x0A;13/F–15/M Floor, WKCDA Tower, No. 8 Austin Road West, West Kowloon Cultural District, Kowloon, Hong Kong">Peak Re</option>
                        <option value="Mapfre&#x0A;P.º de Recoletos 25, 28004 Madrid, Spain">Mapfre</option>
                        <option value="Axis&#x0A;Axis Re Se, Dublin, Zurich Branch, Alfred Escher-Strasse, 50, 8002 Zürich, Switzerland">Axis</option>
                        <option value="Specialty MGA&#x0A;34 Lime Street Office 3.11 & 3.12, C/O Mnk Re Limited, London, EC3M 7AT, UNITED KINGDOM">Specialty MGA</option>
                        <option value="Allianz&#x0A;3 Temasek Ave, Singapore 039190">Allianz</option>
                        <option value="Volante&#x0A;Unit 1, Level 6, 4 North Avenue, Maker Maxity, Bandra Kurla Complex, Bandra East, Mumbai – 400 051, India">Volante</option>
                        <option value="Markel Capital Limited- Syndicate 3000&#x0A;Unit 1, Level 6, 4 North Avenue, Maker Maxity, Bandra Kurla Complex, Bandra East, Mumbai 400 051, India">Markel Capital Limited- Syndicate 3000</option>
                        <option value="Helvetia&#x0A;Zürichstrasse 130, CH‑8600 Dübendorf (Zurich), Switzerland">Helvetia</option>
                        <option value="IRB Brasil&#x0A;Av. República do Chile, 330 (Torre Leste, 3E–4 Andares) Centro, Rio de Janeiro – RJ, CEP 20031‑170, Brazil">IRB Brasil</option>
                        <option value="Liberty&#x0A;42 Rue Washington, Building Monceau — 7th Floor, 75008 Paris, France">Liberty</option>
                        <option value="Polish Re&#x0A;ul. Bytomska 4, 01‑612 Warszawa, Poland">Polish Re</option>
                        <option value="Partner Re&#x0A;Level 38, Room 3837, Sun Hung Kai Centre 30 Harbour Road, Wan Chai, Hong Kong SAR">Partner Re</option>
                        <option value="AEGIS Managing Agency Limited&#x0A;25 Fenchurch Avenue London EC3M 5AD United Kingdom">AEGIS Managing Agency Limited</option>
                        <option value="Renaissance Re&#x0A;Beethovenstrasse 33, CH‑8002 Zürich, Switzerland">Renaissance Re</option>
                        <option value="MS Amlin&#x0A;MS Amlin AG, Kirchenweg 5, 8008 Zürich, Switzerland">MS Amlin</option>
                        <option value="Hiscox&#x0A;Hiscox – 22 Bishopsgate London EC2N 4BQ, United Kingdom">Hiscox</option>
                        <option value="Trans Re&#x0A;Sihlstrasse 38, PO Box 8021, 8001 Zürich, Switzerland">Trans Re</option>
                        <option value="Hartford&#x0A;Hartford, Connecticut 06155‑0001 United States">Hartford</option>
                        <option value="Arch Re&#x0A;Talstrasse 65, 7th Floor, CH‑8001 Zürich, Switzerland">Arch Re</option>
                        <option value="Convex&#x0A;52 Lime Street London EC3M 7AF United Kingdom">Convex</option>
                        <option value="SCR Morocco&#x0A;Tour ATLAS, Place Zellaqa B.P. 13183 Casablanca, Morocco">SCR Morocco</option>
                        <option value="Munich Re&#x0A;Unit 1101, B Wing, The Capital, Plot no. C-70, G Block, Bandra Kurla Complex (BKC), Bandra (East), Mumbai 400051, India">Munich Re</option>
                        <option value="Swiss Re&#x0A;A 701, 7th Floor, One BKC, Plot No. C-66 G Block, Bandra Kurla Complex, Mumbai 400051, India">Swiss Re</option>
                        <option value="GIC Re&#x0A;Suraksha, 170, Jamshedji Tata Road, Churchgate, Mumbai 400020, India">GIC Re</option>
                        <option value="Hannover Re&#x0A;B Wing, Unit No. 604, 6th Floor, Fulcrum, Sahar Road, Andheri (East), Mumbai 400099, India">Hannover Re</option>
                        <option value="Antares&#x0A;21 Lime Street London EC3M 7HB United Kingdom">Antares</option>
                        <option value="Inigo&#x0A;6th Floor, 10 Fenchurch Avenue London EC3M 5AG United Kingdom">Inigo</option>
                        <option value="Descartes&#x0A;5 Shenton Way, #22-04, UIC Building, Singapore 068808">Descartes</option>
                        <option value="Klapton&#x0A;ACS 69, Mutsamudu, Autonomous Island of Anjouan, Union of Comoros">Klapton</option>
                        <option value="Allianz Commercial&#x0A;Office No. 66, 6th Floor, 3 North Avenue, Maker Maxity, Bandra Kurla Complex, Bandra (East), Mumbai – 400051, Maharashtra, India.">Allianz Commercial</option>
                    </select>
                </div>

                <div style="margin-bottom:1.5rem;">
                    <div style="margin-bottom:0.7rem; display:flex; gap:1rem; align-items:center;">
                        <label style="margin-bottom:0; color:#2e3192; font-weight:500;">Select Policy Wording Template:</label>
                        <select id="policyWordingTemplateSelect" style="padding:0.4rem 1.2rem; border-radius:6px; border:1.5px solid #e3f0ff; background:#f7faff; font-size:0.95rem;">
                            <option value="1">Template 1</option>
                            <option value="2">Tempalate 2</option>
                        </select>
                        <button type="button" onclick="insertPolicyWordingTemplate()" style="background:#2e3192; color:#fff; border:none; border-radius:6px; padding:0.4rem 1.2rem; font-size:0.95rem; cursor:pointer;">Insert Selected Template</button>
                    </div>
                    <textarea id="policy_wording" name="policy_wording" class="form-input" rows="12"></textarea>
                    <script>
                    function insertPolicyWordingTemplate() {
                        var select = document.getElementById('policyWordingTemplateSelect');
                        var html = '';
                        if(select.value === '1') {
                            html = `<div class=\"WordSection1\">\n\n<h1 style=\"margin-top:2.3pt;margin-right:0cm;margin-bottom:0cm;margin-left:1.3pt;margin-bottom:.0001pt;text-align:justify;text-indent:0cm\"></h1><p></p><p></p>\n<p data-start=\"0\" data-end=\"159\"><br></p><hr data-start=\"161\" data-end=\"164\"><p data-start=\"166\" data-end=\"222\"><strong data-start=\"166\" data-end=\"222\">POLITICAL VIOLENCE INSURANCE PROPERTY DAMAGE WORDING</strong></p><hr data-start=\"224\" data-end=\"227\"><h3 data-start=\"229\" data-end=\"254\">1. BASIS OF INSURANCE</h3><p data-start=\"256\" data-end=\"509\">All information provided to Underwriters by the Insured and/or its agent(s) in connection with this insurance, including but not limited to the Proposal Form specified in item 10 of Schedule 1, forms the basis of and is incorporated into this insurance.</p><hr data-start=\"511\" data-end=\"514\"><h3 data-start=\"516\" data-end=\"538\">2. INSURING CLAUSE</h3><p>\n\n\n\n\n\n\n\n\n</p><p data-start=\"540\" data-end=\"785\">In consideration of the premium paid and subject to the exclusions, limits and conditions contained herein, this Policy indemnifies the Insured for its ascertained Net Loss for any one Occurrence up to but not exceeding the Policy Limit against:</p><p><strong>2.1</strong> Physical loss or damage to the Buildings and Contents which belong to the Insured or for which the Insured is legally responsible, directly caused by one or more of the following perils occurring during the Policy Period and for which cover has been purchased as specified in item 4 of Schedule 1:</p><ol>\n<li>\n<p>Act of Terrorism</p>\n</li>\n<li>\n<p>Sabotage</p>\n</li>\n<li>\n<p>Riots, Strikes and/or Civil Commotion</p>\n</li>\n<li>\n<p>Malicious Damage</p>\n</li>\n<li>\n<p>Insurrection, Revolution or Rebellion</p>\n</li>\n<li>\n<p>Mutiny and/or Coup d'État</p>\n</li>\n<li>\n<p>War and/or Civil War</p>\n</li>\n</ol><p>Such perils as are specified in item 4 of Schedule 1 and in respect of which cover has been purchased by the Insured shall be the <em>Covered Causes of Loss</em>.</p><p><strong>2.2</strong> Expenses incurred by the Insured in the removal of debris directly caused by one or more of the Covered Causes of Loss. The cost of such removal shall not be considered in determining the property valuation.</p><p><strong>2.3</strong> The Underwriters shall not be liable for more than the Policy Limit stated in item 5 of Schedule 1 for any one Occurrence and in the aggregate. The limit under Clauses 2.1 and 2.2 shall not exceed the Policy Limit.</p><hr><h3>3. DEFINITIONS</h3><p><strong>Act of Terrorism</strong> – unlawful act, including the use of force or violence, committed for political, religious or ideological purposes to influence any government and/or to put the public in fear.</p><p><strong>Actual Cash Value</strong> – cost to repair or replace Buildings or Contents with deduction for wear, tear and obsolescence.</p><p><strong>Buildings</strong> – any roofed and walled structure, machinery, equipment, signs, glass, lifts, fuel tanks, driveways, walls, gates, satellite dishes and fittings, owned or legally responsible by the Insured and situated at an Insured Location.<br>When declared and agreed by Underwriters, includes underground mines, tunnels, wells, caverns, dams, shafts, dikes, levees, flumes, etc.</p><p><strong>Civil Commotion</strong> – same as Riots.</p><p><strong>Civil War</strong> – war carried on between or among opposing citizens of the same country or nation.</p><p><strong>Contents</strong> – fixtures, fittings, office furniture, interior decorations, and stock (including finished goods), owned or legally responsible by the Insured at an Insured Location.</p><p><strong>Coup d'État</strong> – sudden, violent, and illegal overthrow of a sovereign government or attempt thereof.</p><p><strong>Declared Values</strong> – values stated in Schedule 2.</p><p><strong>Deductible</strong> – deductible(s) stated in item 7 of Schedule 1, applicable to each Occurrence.</p><p><strong>Electronic Data</strong> – data, programs, software, or coded instructions for use in electronic or electromechanical equipment.</p><p><strong>Insured</strong> – entity or entities stated in item 1 of Schedule 1.<br><strong>Insured Country</strong> – country where the Insured’s principal business is located.<br><strong>Insured Location</strong> – locations described in Schedule 2.</p><p><strong>Insurrection, Revolution, Rebellion</strong> – deliberate, organised and open resistance by force against a sovereign government.</p><p><strong>Malicious Damage</strong> – physical loss or damage from a malicious act during public disturbance motivated by political reasons.</p><p><strong>Mutiny</strong> – wilful resistance by legally armed forces to a superior officer.</p><p><strong>Net Loss</strong> –<br>For Buildings: reasonable cost to repair, replace or reinstate to the same condition prior to loss, subject to:</p><ol>\n<li>\n<p>Repairs executed with due diligence.</p>\n</li>\n<li>\n<p>If not repaired/replaced within a reasonable time, payment limited to Actual Cash Value.</p>\n</li>\n<li>\n<p>Increased cost due to legal restrictions limited to Policy Sub-Limit in item 6 of Schedule 1.</p>\n</li>\n</ol><p>For Contents:<br>(i) Finished goods – regular selling price less discounts.<br>(ii) Other stock – raw material and labour value.<br>(iii) Property of others – legal liability, up to Actual Cash Value.<br>(iv) Media – cost of blank media plus copying from backups.<br>(v) Documents – cost of blank material plus labour to copy.<br>(vi) Other property – Actual Cash Value.</p><p>For debris removal: reasonable expenses incurred with Underwriters’ consent.<br>All amounts based on date of loss and subject to Policy Limit.</p><p><strong>Occurrence</strong> – one loss or series of losses from one act or cause within any 72-hour period.</p><p><strong>Operations</strong> – the Insured’s business operations at one or more Insured Locations.<br><strong>Policy Period</strong> – period stated in item 3 of Schedule 1.<br><strong>Policy Limit</strong> – limit stated in item 5 of Schedule 1 (aggregate for all losses).<br><strong>Policy Sub-Limit</strong> – sub-limit stated in item 6 of Schedule 1 (forms part of Policy Limit).<br><strong>Riots</strong> – politically motivated acts committed during public disturbance or acts by lawful authority to suppress such.<br><strong>Strikes</strong> – wilful acts by strikers or locked-out workers or by lawful authority suppressing such.<br><strong>Sabotage</strong> – wilful physical damage or destruction for political reasons.<br><strong>War</strong> – armed conflict between two or more sovereign nations, declared or undeclared.<br><strong>Underwriters</strong> – underwriters and insurers subscribing to this Policy.</p><hr><h3>4. EXCLUSIONS</h3><p>This Policy DOES NOT INDEMNIFY AGAINST:</p><ol>\n<li>\n<p>Nuclear detonation, reaction, radiation, contamination, or nuclear waste.</p>\n</li>\n<li>\n<p>Seizure, confiscation, nationalisation, requisition, expropriation, detention, occupation, embargo, condemnation, or any loss by law or decree.</p>\n</li>\n<li>\n<p>War between any two or more of: China, France, Russian Federation, United Kingdom, United States of America.</p>\n</li>\n<li>\n<p>Delay, loss of market, income, use, access, business cancellation, depreciation, or increased cost (unless under Business Interruption Extension).</p>\n</li>\n<li>\n<p>Consequential loss or damage (unless under Business Interruption Extension).</p>\n</li>\n<li>\n<p>Third-party liability.</p>\n</li>\n<li>\n<p>Pollution or contamination of any kind.</p>\n</li>\n<li>\n<p>Chemical, biological, biochemical or electromagnetic weapon or exposure.</p>\n</li>\n<li>\n<p>Electronic causes (e.g., hacking, virus), except when mobile phone used as remote trigger.</p>\n</li>\n<li>\n<p>Loss or corruption of Electronic Data.</p>\n</li>\n<li>\n<p>Enforcement of any ordinance or law regarding reconstruction or demolition (except as provided under Clause 3 of \"Net Loss\").</p>\n</li>\n<li>\n<p>Failure or insufficiency of water, gas, electricity, telecommunications or other utilities.</p>\n</li>\n<li>\n<p>Threat or hoax.</p>\n</li>\n<li>\n<p>Burglary, theft, looting, or mysterious disappearance.</p>\n</li>\n<li>\n<p>Suspension, lapse or cancellation of any lease, licence, contract or order.</p>\n</li>\n<li>\n<p>Fraudulent, dishonest or criminal act by any director, officer or trustee.</p>\n</li>\n<li>\n<p>Fines, penalties, or damages for breach of contract.</p>\n</li>\n<li>\n<p>Asbestos or asbestos-containing materials (including removal cost).</p>\n</li>\n<li>\n<p>Insects or vermin.</p>\n</li>\n<li>\n<p>Debt, insolvency, commercial failure, or other financial cause.</p></li></ol></div>`;
                        } else if(select.value === '2') {
                            html = `<div class=\"WordSection1\"><h2 style=\"text-align: center; \">TERRORISM AND/OR SABOTAGE INSURANCE</h2><h3 style=\"text-align: center; \">LIABILITY WORDING</h3><h3 style=\"text-align: center;\">INSURING CLAUSE</h3><p style=\"text-align: left;\">This is a <strong>Claims Made and Reported Contract</strong>, which applies only to claims first made against the Insured during this Contract’s Period.</p><p>This Contract is <strong>not subject to the terms or conditions of any other insurance</strong> and should be read carefully by the Insured.</p><p>In consideration of the payment of the premium, Insurers agree—subject to the insuring agreements, conditions, exclusions, definitions, and declarations contained herein—to indemnify the Insured in respect of their operations for their <strong>Ultimate Net Loss</strong>, by reason of liability imposed upon the Insured by law, for monetary damages in respect of:</p><p>a) Claims first made against the Insured during this Contract’s Period;<br><strong>or</strong><br>b) Claims, or circumstances likely to give rise to a claim insured hereunder, which are reported in writing to Insurers as soon as reasonably possible and in no event later than <strong>90 days after the expiry</strong> of this Contract.</p><p>Provided always that such Claims arise out of an <strong>Occurrence</strong> (as defined herein) that takes place during this Contract’s Period, for:</p><ul><li><p>Bodily Injury</p></li><li><p>and/or Property Damage</p></li><li><p>and/or Defence Expenses</p></li></ul><p>Resulting solely and directly from an <strong>Act (or Acts) of an Insured Event(s)</strong>, as defined below.</p><hr><h3>DEFINITIONS</h3><p><strong>1. Insured Event(s):</strong></p><ol><li><p>“Terrorism” shall mean an act or series of acts, including the use of force or violence, of any person or group(s) of persons, whether acting alone or on behalf of or in connection with any organisation(s), committed for political, religious, or ideological purposes including the intention to influence any government and/or to put the public in fear for such purposes.</p></li><li><p>“Sabotage” shall mean a subversive act or series of such acts committed for political, religious, or ideological purposes including the intention to influence any government and/or to put the public in fear for such purposes.</p></li></ol><p><strong>2. Bodily Injury:</strong><br>All physical injury to a third-party human being including death, sickness, disease or disability, and all mental injury, anguish, or shock to such person resulting from such physical injury.</p><p><strong>3. Claim:</strong><br>That part of each written demand received by the Insured for monetary damages covered by this Contract, including the service of suit or institution of arbitration proceedings.<br>The term “Claim” shall not include a demand for an injunction or any other non-monetary relief.</p><p><strong>4. Defence Expenses:</strong><br>Investigation, adjustment, approval, defence, and appeal costs and expenses, including pre- and post-judgement interest, paid or incurred by or on behalf of the Insured.<br>The salaries, expenses, or administrative costs of the Insured or its employees or any insurer shall not be included within the meaning of Defence Expenses.</p><p><strong>5. Property Damage:</strong><br>Physical loss of, or damage to, tangible property of a third party, including loss of use and/or removal of debris from third-party property.</p><p><strong>6. Ultimate Net Loss:</strong><br>The amount an Insured is obligated to pay, by judgment or settlement, as damages resulting from a claim, including defence expenses in respect of such claim arising out of one Occurrence.<br>It is agreed that the limit of liability available to pay damages shall be reduced and may be completely exhausted by payment of claim expenses.</p><p><strong>7. Occurrence:</strong><br>Claims arising out of and directly occasioned by one act or series of related acts of an Insured Event for the same purpose or cause.<br>The duration and extent of any one Occurrence shall be limited to all Claims directly occasioned by one act or series of acts of an Insured Event arising out of the same purpose or cause during any period of <strong>72 consecutive hours</strong>, commencing at the time of the first such act, and within a radius of <strong>10 miles</strong> of the first act of Terrorism.<br>No period of 72 consecutive hours shall commence prior to the attachment of this Contract.</p><p><strong>8. Limit of Liability:</strong><br>The Ultimate Net Loss (as set out in the Risk Details) in excess of the underlying amount and/or each Occurrence retention.<br>Regardless of the number of Occurrences or Claims, the Insurers’ total limit of liability—including defence expenses—shall not exceed the amount of Ultimate Net Loss.<br>The limit stated as “aggregate” is the total limit of Insurers’ liability for all damages and expenses arising out of all Claims first made during the Contract period.</p><p><strong>9. Excess:</strong><br>The underlying amount and/or each Occurrence retention (as set out in the Risk Details).<br>The Insured shall always be liable for this amount in respect of each and every Occurrence.</p><hr><h3>EXCLUSIONS</h3><p>This Contract <strong>does NOT apply</strong> to any actual or alleged loss, damage, liability, injury, or expense arising directly or indirectly from or as a result of:</p><ol><li><p>Nuclear detonation, reaction, radiation, or contamination.</p></li><li><p>War, invasion, civil war, rebellion, revolution, insurrection, or martial law.</p></li><li><p>Seizure or illegal occupation unless caused directly by an Insured Event.</p></li><li><p>Confiscation, nationalisation, requisition, detention, embargo, or quarantine by order of public or government authority.</p></li><li><p>Discharge of pollutants or contaminants.</p></li><li><p>Chemical or biological release or exposure.</p></li><li><p>Attacks by electronic means (e.g., hacking, virus, electromagnetic weapon) except where such systems are part of a weapon or missile.</p></li><li><p>Vandals, protesters, strikes, riots, or civil commotion.</p></li><li><p>Loss of use, delay, loss of markets, failure to perform, or consequential loss unless covered by a business interruption extension.</p></li><li><p>Failure or fluctuation of utility or telecommunication services not on the Insured’s premises.</p></li><li><p>Threat or hoax.</p></li><li><p>Burglary, looting, theft, or larceny.</p></li><li><p>Bodily Injury to employees or contract workers of the Insured, or claims under employment or compensation laws.</p></li><li><p>Property owned, leased, rented, or in the Insured’s care, custody, or control.</p></li><li><p>Fines, penalties, punitive or exemplary damages.</p></li><li><p>Mental injury or shock where no Bodily Injury occurred.</p></li><li><p>Criminal, dishonest, fraudulent, or malicious conduct by the Insured.</p></li><li><p>Goods or products designed, manufactured, or sold by the Insured.</p></li><li><p>Claims or circumstances disclosed on the application for this insurance.</p></li></ol><p><em>Nothing in the above exclusions shall extend this Contract to cover any liability which would not have been covered had these exclusions not been included.</em></p></div>`;
                        }
                        
                        // Check if Summernote is initialized
                        if(typeof $.summernote !== 'undefined' && $('#policy_wording').data('summernote')) {
                            $('#policy_wording').summernote('code', html);
                        } else {
                            document.getElementById('policy_wording').value = html;
                        }
                    }
                    </script>
                </div>

                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-primary btn-lg">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Bootstrap CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.20/dist/summernote-lite.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.20/dist/summernote-lite.min.js"></script>
<script>
    $(document).ready(function() {
        $('#policy_wording').summernote({
            height: 300,
            tabsize: 2,
            toolbar: [
                ['style', ['style']],
                ['font', ['bold', 'italic', 'underline', 'clear']],
                ['fontname', ['fontname']],
                ['fontsize', ['fontsize']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['insert', ['link', 'picture', 'video', 'table', 'hr']],
                ['view', ['fullscreen', 'codeview', 'help']]
        ],
            fontNames: ['Arial', 'Arial Black', 'Calibri', 'Comic Sans MS', 'Courier New', 'Times New Roman', 'Verdana'],
            fontSizes: ['8', '9', '10', '11', '12', '14', '16', '18', '20', '22', '24', '28', '32', '36', '48', '64', '82', '150']
        });
    });

    // Currency label update function
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
    }

    // Add more indemnity limits
    function addIndemnityLimit() {
        const wrapper = document.getElementById('indemnityLimitsWrapper');
        const div = document.createElement('div');
        div.style.display = 'flex';
        div.style.gap = '0.5rem';
        div.style.marginBottom = '0.5rem';
        div.style.alignItems = 'center';
        div.innerHTML = `
            <input type="text" name="limit_of_indemnity[]" class="form-input" placeholder="Enter limit of indemnity" style="flex:1;">
            <button type="button" onclick="removeIndemnityLimit(this)" style="background:#e74c3c; color:#fff; border:none; border-radius:6px; padding:0.3rem 0.8rem; font-size:1rem; cursor:pointer;">&times;</button>
        `;
        wrapper.appendChild(div);
    }

    // Remove indemnity limit
    function removeIndemnityLimit(btn) {
        btn.parentElement.remove();
    }

    // Add more additional covers
    function addAdditionalCover() {
        const wrapper = document.getElementById('additionalCoversWrapper');
        const div = document.createElement('div');
        div.style.display = 'flex';
        div.style.gap = '0.5rem';
        div.style.marginBottom = '0.5rem';
        div.style.alignItems = 'center';
        div.innerHTML = `
            <input type="text" name="additional_covers[]" class="form-input" placeholder="Enter additional cover" style="flex:1;">
            <button type="button" onclick="removeAdditionalCover(this)" style="background:#e74c3c; color:#fff; border:none; border-radius:6px; padding:0.3rem 0.8rem; font-size:1rem; cursor:pointer;">&times;</button>
        `;
        wrapper.appendChild(div);
    }

    // Remove additional cover
    function removeAdditionalCover(btn) {
        btn.parentElement.remove();
    }

    // Add more deductibles
    function addDeductible() {
        const wrapper = document.getElementById('deductiblesWrapper');
        const div = document.createElement('div');
        div.style.display = 'flex';
        div.style.gap = '0.5rem';
        div.style.marginBottom = '0.5rem';
        div.style.alignItems = 'center';
        div.innerHTML = `
            <input type="text" name="deductibles[]" class="form-input" placeholder="Enter deductible" style="flex:1;">
            <button type="button" onclick="removeDeductible(this)" style="background:#e74c3c; color:#fff; border:none; border-radius:6px; padding:0.3rem 0.8rem; font-size:1rem; cursor:pointer;">&times;</button>
        `;
        wrapper.appendChild(div);
    }

    // Remove deductible
    function removeDeductible(btn) {
        btn.parentElement.remove();
    }

    // Update total sum insured
    function updateTotalSumInsured() {
        const pd = parseFloat(document.getElementById('property_damage').value) || 0;
        const bi = parseFloat(document.getElementById('business_interruption').value) || 0;
        document.getElementById('total_sum_insured').value = pd + bi;
    }

    // Add risk location
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
            <input type="text" name="risk_location[]" class="form-input" style="flex:1 1 200px;" placeholder="Enter risk location">
            <input type="number" name="risk_property_damage[]" class="form-input" style="flex:1 1 150px;" placeholder="Property Damage" min="0" step="any" oninput="calculateRiskTotal(this)">
            <input type="number" name="risk_business_interruption[]" class="form-input" style="flex:1 1 150px;" placeholder="Business Interruption" min="0" step="any" oninput="calculateRiskTotal(this)">
            <input type="number" name="risk_total_sum[]" class="form-input" style="flex:1 1 150px;" placeholder="Total Sum Insured" readonly style="background:#e3f0ff;">
            <button type="button" onclick="removeRiskLocation(this)" style="background:#e74c3c; color:#fff; border:none; border-radius:6px; padding:0.3rem 0.8rem; font-size:1rem; cursor:pointer;">&times;</button>
        `;
        wrapper.appendChild(div);
    }

    // Remove risk location
    function removeRiskLocation(btn) {
        btn.parentElement.remove();
    }

    // Calculate total sum for each risk location
    function calculateRiskTotal(input) {
        const container = input.closest('div');
        const propertyDamage = parseFloat(container.querySelector('input[name="risk_property_damage[]"]').value) || 0;
        const businessInterruption = parseFloat(container.querySelector('input[name="risk_business_interruption[]"]').value) || 0;
        const totalField = container.querySelector('input[name="risk_total_sum[]"]');
        totalField.value = propertyDamage + businessInterruption;
    }

    // Toggle risk location annexure
    function toggleRiskLocationAnnexure() {
        var annexureCheckbox = document.getElementById('riskLocationAnnexure');
        var wrapper = document.getElementById('riskLocationsWrapper');
        var headers = document.getElementById('riskLocationHeaders');
        var hidden = document.getElementById('riskLocationAnnexureHidden');
        if (annexureCheckbox.checked) {
            wrapper.style.display = 'none';
            headers.style.display = 'none';
            hidden.value = '1';
        } else {
            wrapper.style.display = 'block';
            headers.style.display = 'flex';
            hidden.value = '0';
        }
    }

    // Toggle policy period TBA
    function togglePolicyPeriodTBA(checkbox) {
        const startDate = document.getElementById('policy_start_date');
        const endDate = document.getElementById('policy_end_date');
        if (checkbox.checked) {
            startDate.disabled = true;
            endDate.disabled = true;
            startDate.value = '';
            endDate.value = '';
        } else {
            startDate.disabled = false;
            endDate.disabled = false;
        }
    }

    // Toggle placement type field (PPW/PPC)
    function togglePlacementTypeField() {
        const ppwRadio = document.getElementById('ppw');
        const ppcRadio = document.getElementById('ppc');
        const ppwField = document.getElementById('ppwField');
        const ppcField = document.getElementById('ppcField');
        
        if (ppwRadio.checked) {
            ppwField.style.display = 'block';
            ppcField.style.display = 'none';
            document.getElementById('ppc_input').value = ''; // Clear PPC field
        } else if (ppcRadio.checked) {
            ppwField.style.display = 'none';
            ppcField.style.display = 'block';
            document.getElementById('ppw_input').value = ''; // Clear PPW field
        }
    }
</script>
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
    .note-editor.note-frame {
        border-radius: 8px;
        border: 1.5px solid #e3f0ff;
    }
</style>
@endsection
