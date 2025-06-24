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
                            <option value="Terrorism and Sabotage"
                                {{ $quote->policy_name == 'Terrorism and Sabotage' ? 'selected' : '' }}>Terrorism and
                                Sabotage</option>
                            <option value="Terrorism Liability"
                                {{ $quote->policy_name == 'Terrorism Liability' ? 'selected' : '' }}>Terrorism Liability
                            </option>
                            <option value="Professional Indemnity"
                                {{ $quote->policy_name == 'Professional Indemnity' ? 'selected' : '' }}>Professional
                                Indemnity Policy</option>
                            <option value="Cyber Insurance"
                                {{ $quote->policy_name == 'Cyber Insurance' ? 'selected' : '' }}>Cyber Insurance</option>
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
                    </div>
                </div>
                <div style="display: flex; flex-wrap: wrap; gap: 1.5rem; margin-top:1.2rem;">
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
                    <div id="riskLocationsWrapper">
                        @php
                            $riskLocations = $quote->risk_locations ? json_decode($quote->risk_locations, true) : [''];
                        @endphp
                        @foreach ($riskLocations as $loc)
                            <input type="text" name="risk_locations[]" class="form-input" value="{{ $loc }}"
                                placeholder="Enter risk location">
                        @endforeach
                    </div>
                    <button type="button" onclick="addRiskLocation()"
                        style="margin-top:0.5rem; background:#2e3192; color:#fff; border:none; border-radius:6px; padding:0.4rem 1.2rem; font-size:0.95rem; cursor:pointer;">Add
                        More</button>
                </div>
                <!-- Sum Insured Details -->
                <div style="display: flex; flex-wrap: wrap; gap: 1.5rem; margin-top:1.2rem;">
                    <div style="flex:1 1 300px;">
                        <label for="property_damage" style="color:#2e3192; font-weight:600;">Property Damage</label>
                        <input type="number" id="property_damage" name="property_damage" class="form-input" min="0"
                            step="any" value="{{ $quote->property_damage }}" oninput="updateTotalSumInsured()">
                    </div>
                    <div style="flex:1 1 300px;">
                        <label for="business_interruption" style="color:#2e3192; font-weight:600;">Business
                            Interruption</label>
                        <input type="number" id="business_interruption" name="business_interruption" class="form-input"
                            min="0" step="any" value="{{ $quote->business_interruption }}"
                            oninput="updateTotalSumInsured()">
                    </div>
                    <div style="flex:1 1 300px;">
                        <label for="total_sum_insured" style="color:#2e3192; font-weight:600;">Total Sum Insured</label>
                        <input type="number" id="total_sum_insured" name="total_sum_insured" class="form-input"
                            value="{{ $quote->total_sum_insured }}" readonly style="background:#e3f0ff;">
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
                            </select>
                        </div>
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
        // Add more risk locations
        function addRiskLocation() {
            const wrapper = document.getElementById('riskLocationsWrapper');
            const input = document.createElement('input');
            input.type = 'text';
            input.name = 'risk_locations[]';
            input.className = 'form-input';
            input.placeholder = 'Enter risk location';
            input.style.marginTop = '0.5rem';
            wrapper.appendChild(input);
        }
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
@endsection
