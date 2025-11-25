@extends('layouts.app')

@section('title', 'Edit Placement Slip')

@section('content')
<div class="container py-5" style="display: flex; justify-content: center; align-items: center; min-height: 80vh;">
    <div class="card shadow-lg" style="width: 95%; max-width: 1400px; margin: 0 auto;">
        <div class="card-header bg-primary text-white text-center">
            <h3 class="mb-0">Edit Placement Slip</h3>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('placement-slip.update', ['id' => $placementSlip->id]) }}">
                @csrf
                @method('PUT')

                <!-- Quote Information Fields -->
                <div style="display: flex; flex-wrap: wrap; gap: 1.5rem;">
                    <div style="flex:1 1 400px;">
                        <label for="insured_name" style="color:#2e3192; font-weight:600;">Insured Name</label>
                        <input type="text" id="insured_name" name="insured_name" class="form-input"
                            value="{{ old('insured_name', $placementSlip->insured_name) }}">
                    </div>
                    <div style="flex:1 1 400px;">
                        <label for="insured_address" style="color:#2e3192; font-weight:600;">Insured Address</label>
                        <input type="text" id="insured_address" name="insured_address" class="form-input"
                            value="{{ old('insured_address', $placementSlip->insured_address) }}">
                    </div>
                </div>
                
                <div style="display: flex; flex-wrap: wrap; gap: 1.5rem; margin-top:1.2rem;">
                    <div style="flex:1 1 400px;">
                        <label for="policy_name" style="color:#2e3192; font-weight:600;">Policy Name</label>
                        <select id="policy_name" name="policy_name" class="form-input">
                            <option value="">Select Policy</option>
                            <option value="Terrorism and Sabotage and Terrorism Liability Insurance"
                                {{ old('policy_name', $placementSlip->policy_name) == 'Terrorism and Sabotage and Terrorism Liability Insurance' ? 'selected' : '' }}>
                                Terrorism and Sabotage and Terrorism Liability Insurance</option>
                            <option value="Professional Indemnity"
                                {{ old('policy_name', $placementSlip->policy_name) == 'Professional Indemnity' ? 'selected' : '' }}>Professional
                                Indemnity Policy</option>
                            <option value="Cyber Insurance"
                                {{ old('policy_name', $placementSlip->policy_name) == 'Cyber Insurance' ? 'selected' : '' }}>Cyber Insurance</option>
                            <option value="Political Violence Insurance"
                                {{ old('policy_name', $placementSlip->policy_name) == 'Political Violence Insurance' ? 'selected' : '' }}>Political
                                Violence Insurance</option>
                        </select>
                    </div>
                    <div style="flex:1 1 400px;">
                        <label style="color:#2e3192; font-weight:600;">Policy Period</label>
                        @php
                            $period = $placementSlip->policy_period ? explode(' - ', $placementSlip->policy_period) : [null, null];
                            function parseDateForEditInput($dateStr)
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
                                value="{{ parseDateForEditInput($period[0] ?? '') }}" style="flex:1;">
                            <span style="align-self:center;">to</span>
                            <input type="date" id="policy_end_date" name="policy_end_date" class="form-input"
                                value="{{ parseDateForEditInput($period[1] ?? '') }}" style="flex:1;">
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
                            <option value="INR" {{ old('currency_type', $placementSlip->currency_type) == 'INR' ? 'selected' : '' }}>INR</option>
                            <option value="USD" {{ old('currency_type', $placementSlip->currency_type) == 'USD' ? 'selected' : '' }}>Dollar</option>
                            <option value="EUR" {{ old('currency_type', $placementSlip->currency_type) == 'EUR' ? 'selected' : '' }}>Euro</option>
                        </select>
                    </div>
                    <div style="flex:1 1 400px;">
                        <label for="occupancy" style="color:#2e3192; font-weight:600;">Occupancy</label>
                        <input type="text" id="occupancy" name="occupancy" class="form-input"
                            value="{{ old('occupancy', $placementSlip->occupancy) }}">
                    </div>
                    <div style="flex:1 1 400px;">
                        <label for="jurisdiction" style="color:#2e3192; font-weight:600;">Jurisdiction</label>
                        <input type="text" id="jurisdiction" name="jurisdiction" class="form-input"
                            value="{{ old('jurisdiction', $placementSlip->jurisdiction) }}">
                    </div>
                </div>
                
                <!-- Risk Locations (multiple rows) -->
                <div style="margin-top:1.2rem;">
                    <label style="color:#2e3192; font-weight:600;">Risk Locations</label>
                    <div style="margin-bottom:0.7rem;">
                        <input type="checkbox" id="riskLocationAnnexure" name="risk_location_as_per_annexure" value="1"
                            onchange="toggleRiskLocationAnnexure()"
                            {{ old('risk_location_as_per_annexure', $placementSlip->risk_location_as_per_annexure) == 1 ? 'checked' : '' }}>
                        <label for="riskLocationAnnexure" style="color:#2e3192; font-weight:500; margin-left:4px;">As per
                            Annexure</label>
                    </div>
                    <!-- Field Headers -->
                    <div style="display:flex; flex-wrap:wrap; gap:0.5rem; margin-bottom:0.5rem; padding:0.5rem; background:#e3f0ff; border-radius:6px; font-weight:600; color:#2e3192; font-size:0.9rem; {{ old('risk_location_as_per_annexure', $placementSlip->risk_location_as_per_annexure) == 1 ? 'display:none;' : '' }}"
                        id="riskLocationHeaders">
                        <div style="flex:1 1 200px;">Risk Location</div>
                        <div style="flex:1 1 150px;">Property Damage</div>
                        <div style="flex:1 1 150px;">Business Interruption</div>
                        <div style="flex:1 1 150px;">Total Sum Insured</div>
                        <div style="width:40px;">Action</div>
                    </div>
                    <div id="riskLocationsWrapper"
                        style="{{ old('risk_location_as_per_annexure', $placementSlip->risk_location_as_per_annexure) == 1 ? 'display:none;' : '' }}">
                        @php
                            $riskLocations = $placementSlip->risk_locations ? (is_string($placementSlip->risk_locations) ? json_decode($placementSlip->risk_locations, true) : $placementSlip->risk_locations) : [];
                        @endphp
                        @if (old('risk_location_as_per_annexure', $placementSlip->risk_location_as_per_annexure) == 0)
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
                        value="{{ old('risk_location_as_per_annexure', $placementSlip->risk_location_as_per_annexure) }}">
                </div>

                <!-- Sum Insured Details -->
                <div style="display: flex; flex-wrap: wrap; gap: 1.5rem; margin-top:1.2rem;">
                    <div style="flex:1 1 300px;">
                        <label for="property_damage" style="color:#2e3192; font-weight:600;">Property Damage (<span
                                class="currency-label">{{ $placementSlip->currency_type ?? 'INR' }}</span>)</label>
                        <input type="number" id="property_damage" name="property_damage" class="form-input"
                            min="0" step="any" value="{{ old('property_damage', $placementSlip->property_damage) }}"
                            oninput="updateTotalSumInsured()"
                            placeholder="Enter amount in {{ $placementSlip->currency_type ?? 'INR' }}">
                    </div>
                    <div style="flex:1 1 300px;">
                        <label for="business_interruption" style="color:#2e3192; font-weight:600;">Business Interruption
                            (<span class="currency-label">{{ $placementSlip->currency_type ?? 'INR' }}</span>)</label>
                        <input type="number" id="business_interruption" name="business_interruption" class="form-input"
                            min="0" step="any" value="{{ old('business_interruption', $placementSlip->business_interruption) }}"
                            oninput="updateTotalSumInsured()"
                            placeholder="Enter amount in {{ $placementSlip->currency_type ?? 'INR' }}">
                    </div>
                    <div style="flex:1 1 300px;">
                        <label for="total_sum_insured" style="color:#2e3192; font-weight:600;">Total Sum Insured (<span
                                class="currency-label">{{ $placementSlip->currency_type ?? 'INR' }}</span>)</label>
                        <input type="number" id="total_sum_insured" name="total_sum_insured" class="form-input"
                            value="{{ old('total_sum_insured', $placementSlip->total_sum_insured) }}" readonly style="background:#e3f0ff;"
                            placeholder="Amount in {{ $placementSlip->currency_type ?? 'INR' }}">
                    </div>
                </div>
                
                <div style="margin-top:1.2rem;">
                    <label for="coverage" style="color:#2e3192; font-weight:600;">Coverage</label>
                    <input type="text" id="coverage" name="coverage" class="form-input"
                        value="{{ old('coverage', $placementSlip->coverage) }}">
                </div>
                
                <!-- Limit of Indemnity (multiple rows) -->
                <div style="margin-top:1.2rem;">
                    <label style="color:#2e3192; font-weight:600;">Limit of Indemnity</label>
                    <div id="indemnityLimitsWrapper">
                        @php
                            $limits = $placementSlip->limit_of_indemnity ? (is_string($placementSlip->limit_of_indemnity) ? json_decode($placementSlip->limit_of_indemnity, true) : $placementSlip->limit_of_indemnity) : [''];
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
                            value="{{ old('indemnity_period', $placementSlip->indemnity_period) }}">
                    </div>
                    <div style="flex:1 1 400px;">
                        <label style="color:#2e3192; font-weight:600;">Additional Covers Opted</label>
                        <div id="additionalCoversWrapper">
                            @php
                                $additionalCovers = $placementSlip->additional_covers
                                    ? (is_string($placementSlip->additional_covers) ? json_decode($placementSlip->additional_covers, true) : $placementSlip->additional_covers)
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
                    <textarea id="claims" name="claims" class="form-input" rows="2">{{ old('claims', $placementSlip->claims) }}</textarea>
                </div>
                
                <div style="display: flex; flex-wrap: wrap; gap: 1.5rem; margin-top:1.2rem;">
                    <div style="flex:1 1 400px;">
                        <label style="color:#2e3192; font-weight:600;">Deductibles</label>
                        <div id="deductiblesWrapper">
                            @php
                                $deductibles = $placementSlip->deductibles ? (is_string($placementSlip->deductibles) ? json_decode($placementSlip->deductibles, true) : $placementSlip->deductibles) : [''];
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
                            value="{{ old('premium', $placementSlip->premium) }}">
                    </div>
                </div>
                
                <div style="margin-top:1.2rem;">
                    <label for="support" style="color:#2e3192; font-weight:600;">Support</label>
                    <input type="text" id="support" name="support" class="form-input"
                        value="{{ old('support', $placementSlip->support) }}">
                </div>
                
                <!-- Remark Fields -->
                <div style="margin-top:1.5rem;">
                    <div style="display: flex; flex-wrap: wrap; gap: 1.5rem;">
                        <div style="flex:1 1 300px;">
                            <label for="remark1" style="color:#2e3192; font-weight:600;">Remark 1</label>
                            <textarea id="remark1" name="remark1" class="form-input" rows="2">{{ old('remark1', $placementSlip->remark1) }}</textarea>
                        </div>
                        <div style="flex:1 1 300px;">
                            <label for="remark2" style="color:#2e3192; font-weight:600;">Remark 2</label>
                            <textarea id="remark2" name="remark2" class="form-input" rows="2">{{ old('remark2', $placementSlip->remark2) }}</textarea>
                        </div>
                        <div style="flex:1 1 300px;">
                            <label for="remark3" style="color:#2e3192; font-weight:600;">Remark 3</label>
                            <textarea id="remark3" name="remark3" class="form-input" rows="2">{{ old('remark3', $placementSlip->remark3) }}</textarea>
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
                        <input type="radio" id="ppw" name="placement_type" value="PPW" 
                            {{ old('placement_type', $placementSlip->placement_type) == 'PPW' ? 'checked' : '' }} onchange="togglePlacementTypeField()">
                        <label for="ppw" style="color:#2e3192; font-weight:500; margin-left:4px; margin-right:1.5rem;">PPW (Placing and Policy Wording)</label>
                        
                        <input type="radio" id="ppc" name="placement_type" value="PPC" 
                            {{ old('placement_type', $placementSlip->placement_type) == 'PPC' ? 'checked' : '' }} onchange="togglePlacementTypeField()">
                        <label for="ppc" style="color:#2e3192; font-weight:500; margin-left:4px;">PPC (Placing and Policy Cover)</label>
                    </div>
                    
                    <!-- PPW Input Field -->
                    <div id="ppwField" style="margin-top:1rem; {{ old('placement_type', $placementSlip->placement_type) == 'PPW' ? '' : 'display:none;' }}">
                        <label for="ppw_input" style="color:#2e3192; font-weight:600;">PPW Details</label>
                        <textarea id="ppw_input" name="ppw_details" class="form-input" rows="4" placeholder="Enter Placing and Policy Wording details...">{{ old('ppw_details', $placementSlip->ppw_details) }}</textarea>
                    </div>
                    
                    <!-- PPC Input Field -->
                    <div id="ppcField" style="margin-top:1rem; {{ old('placement_type', $placementSlip->placement_type) == 'PPC' ? '' : 'display:none;' }}">
                        <label for="ppc_input" style="color:#2e3192; font-weight:600;">PPC Details</label>
                        <textarea id="ppc_input" name="ppc_details" class="form-input" rows="4" placeholder="Enter Placing and Policy Cover details...">{{ old('ppc_details', $placementSlip->ppc_details) }}</textarea>
                    </div>
                </div>

                <div class="mb-4">
                    <label for="reinsurer_name" class="form-label fw-bold">Reinsurer Name</label>
                    <input type="text" id="reinsurer_name" name="reinsurer_name" class="form-input" 
                        value="{{ old('reinsurer_name', $placementSlip->reinsurer_name) }}" readonly 
                        style="width:100%; background:#e3f0ff; cursor:not-allowed;">
                </div>

                <div class="mb-4">
                    <label for="to" class="form-label fw-bold">To</label>
                    <input type="text" id="to" name="to" class="form-input" 
                        value="{{ old('to', $placementSlip->to) }}" readonly 
                        style="width:100%; background:#e3f0ff; cursor:not-allowed;">
                </div>

                <div style="margin-bottom:1.5rem;">
                    <label for="policy_wording" style="color:#2e3192; font-weight:600;">Policy Wording (Rich Text / HTML)</label>
                    <textarea id="policy_wording" name="policy_wording" class="form-input" rows="12">{{ old('policy_wording', $placementSlip->policy_wording ?? '') }}</textarea>
                </div>

                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-success btn-lg">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>

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

        // Initialize placement type field visibility
        togglePlacementTypeField();
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
